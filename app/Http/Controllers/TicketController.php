<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->bookings()
            ->with(['screening.movie', 'screening.studio', 'seats'])
            ->latest();

        // Filter by status
        $status = $request->input('status', 'all');
        if ($status && $status !== 'all') {
            if (in_array($status, ['paid', 'pending', 'failed'])) {
                $query->where('status', $status);
            } elseif ($status === 'upcoming') {
                $query->where('status', 'paid')
                    ->whereHas('screening', function ($q) {
                        $q->where('start_time', '>', now());
                    });
            } elseif ($status === 'past') {
                $query->where('status', 'paid')
                    ->whereHas('screening', function ($q) {
                        $q->where('start_time', '<=', now());
                    });
            }
        }

        // Search by movie title
        $search = $request->input('search');
        if ($search) {
            $query->whereHas('screening.movie', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        $tickets = $query->paginate(10)->appends($request->only(['status', 'search']));

        return view('tickets.index', compact('tickets', 'status', 'search'));
    }

    public function show(Booking $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        // if ($ticket->user_id !== Auth::id()) {
        //     abort(403);
        // }

        $ticket->load(['screening.movie', 'screening.studio', 'seats']);

        return view('tickets.show', compact('ticket'));
    }

    public function downloadTicket(Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        // if ($ticket->booking->user_id !== Auth::id()) {
        //     abort(403);
        // }


        $ticket->load(['booking.screening.movie', 'booking.screening.studio', 'booking.user', 'seat']);

        $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($ticket->ticket_code));

        $pdf = PDF::loadView('tickets.pdf', compact('ticket', 'qrCode'));

        return $pdf->download(sprintf(
            'ticket-%s-%s.pdf',
            Str::slug($ticket->booking->screening->movie->title),
            $ticket->ticket_code
        ));
    }

    public function downloadAllTickets(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['screening.movie', 'screening.studio', 'tickets.seat', 'user']);
        // Create temporary directory for storing individual PDFs
        $tempDir = storage_path('app/temp/' . uniqid());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Generate individual PDFs
        $pdfFiles = [];
        foreach ($booking->tickets as $ticket) {
            $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($ticket->ticket_code));

            $pdf = PDF::loadView('tickets.pdf', compact('ticket', 'qrCode'));
            $filename = $tempDir . '/' . $ticket->ticket_code . '.pdf';
            $pdf->save($filename);
            $pdfFiles[] = $filename;
        }

        // Merge PDFs
        $merger = new \Jurosh\PDFMerge\PDFMerger;
        foreach ($pdfFiles as $file) {
            $merger->addPDF($file);
        }

        // Save merged PDF
        $outputFile = $tempDir . '/merged.pdf';
        $merger->merge('file', $outputFile);

        // Clean up individual PDFs
        foreach ($pdfFiles as $file) {
            unlink($file);
        }

        // Download merged PDF
        $response = response()->download(
            $outputFile,
            sprintf(
                'tickets-%s-%s.pdf',
                Str::slug($booking->screening->movie->title),
                $booking->id
            )
        );


        return $response;
    }

    public function verify(Request $request)
    {
        try {
            $ticketCode = $request->input('ticket_code');
            Log::info('Verifikasi tiket dengan kode: ' . $ticketCode);

            $ticket = Ticket::where('ticket_code', $ticketCode)
                ->with(['booking.screening.movie', 'booking.user', 'seat'])
                ->first();

            if (!$ticket) {
                Log::warning('Tiket tidak ditemukan dengan kode: ' . $ticketCode);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket tidak valid'
                ], 404);
            }

            // Validasi relasi
            if (!$ticket->booking || !$ticket->booking->screening || !$ticket->booking->screening->movie) {
                Log::error('Data tiket tidak lengkap untuk kode: ' . $ticketCode);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tiket tidak lengkap'
                ], 400);
            }

            // Validasi status booking
            if ($ticket->booking->status !== 'paid') {
                Log::warning('Booking belum dibayar untuk tiket: ' . $ticketCode . ', status: ' . $ticket->booking->status);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking belum dibayar'
                ], 400);
            }

            $screening = $ticket->booking->screening;
            $movie = $screening->movie;
            $waktuTayang = $screening->start_time instanceof Carbon
                ? $screening->start_time
                : Carbon::parse($screening->start_time);
            $durasi = (int) $movie->duration;

            if ($durasi <= 0) {
                Log::error('Durasi film tidak valid: ' . $durasi . ' untuk film: ' . $movie->title);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Durasi film tidak valid'
                ], 400);
            }

            $waktuSelesai = $waktuTayang->copy()->addMinutes($durasi);
            $now = now();
            $toleransiAwal = $waktuTayang->copy()->subMinutes(30); // Ubah dari 15 ke 30 menit
            $toleransiAkhir = $waktuSelesai->copy()->addMinutes(15); // Tambah toleransi 15 menit setelah film selesai

            Log::info('Waktu verifikasi:', [
                'now' => $now->format('Y-m-d H:i:s'),
                'start_time' => $waktuTayang->format('Y-m-d H:i:s'),
                'end_time' => $waktuSelesai->format('Y-m-d H:i:s'),
                'toleransi_awal' => $toleransiAwal->format('Y-m-d H:i:s'),
                'toleransi_akhir' => $toleransiAkhir->format('Y-m-d H:i:s')
            ]);

            if ($now->lt($toleransiAwal)) {
                Log::info('Tiket belum bisa dipakai, waktu sekarang: ' . $now->format('Y-m-d H:i:s') . ', toleransi awal: ' . $toleransiAwal->format('Y-m-d H:i:s'));
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket belum bisa dipakai (bisa diverifikasi 30 menit sebelum film mulai)'
                ], 400);
            }

            if ($now->gt($toleransiAkhir)) {
                Log::info('Tiket tidak berlaku lagi, waktu sekarang: ' . $now->format('Y-m-d H:i:s') . ', toleransi akhir: ' . $toleransiAkhir->format('Y-m-d H:i:s'));
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket tidak berlaku lagi'
                ], 400);
            }

            if ($ticket->is_used) {
                $usedAt = $ticket->used_at ? $ticket->used_at->format('Y-m-d H:i:s') : 'Unknown';
                Log::info('Tiket sudah digunakan: ' . $ticketCode . ' pada: ' . $usedAt);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket sudah digunakan'
                ], 400);
            }

            // Valid window - mark as used
            $ticket->is_used = true;
            $ticket->used_at = now();
            $ticket->save();

            Log::info('Tiket berhasil diverifikasi: ' . $ticketCode);

            // Pastikan start_time adalah Carbon instance
            $startTime = $ticket->booking->screening->start_time instanceof Carbon
                ? $ticket->booking->screening->start_time
                : Carbon::parse($ticket->booking->screening->start_time);

            return response()->json([
                'status' => 'success',
                'message' => 'Tiket berhasil diverifikasi',
                'data' => [
                    'ticket' => $ticket->load('booking.screening.movie'),
                    'seat' => $ticket->seat->row_letter . $ticket->seat->seat_number,
                    'movie' => $ticket->booking->screening->movie->title,
                    'studio' => $ticket->booking->screening->studio->name,
                    'date' => $startTime->format('d M Y H:i')
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('Verifikasi tiket error: ' . $e->getMessage(), [
                'ticket_code' => $request->input('ticket_code'),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function see(Booking $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        // if ($ticket->user_id !== Auth::id()) {
        //     abort(403);
        // }

        $ticket->load(['screening.movie', 'screening.studio', 'tickets.seat']);

        // Generate QR for each ticket
        $qrCodes = [];
        foreach ($ticket->tickets as $ticketSeat) {
            $qrCodes[$ticketSeat->id] = QrCode::format('svg')->size(200)->generate($ticketSeat->ticket_code);
        }

        return view('tickets.see', compact('ticket', 'qrCodes'));
    }
}
