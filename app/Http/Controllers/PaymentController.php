<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPaidMail;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function create(Booking $booking)
    {
        // Load the necessary relationships
        $booking->load(['screening.movie', 'screening.studio', 'tickets.seat']);

        // Create Midtrans transaction parameters
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $booking->id . '-' . time(),
                'gross_amount' => $booking->total_price + 5000, // Including service fee
            ],
            'custom_expiry' => [
                'expiry_duration' => 5,
                'unit' => 'minute'
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => [
                [
                    'id' => $booking->screening->movie->id,
                    'price' => $booking->screening->price,
                    'quantity' => $booking->tickets->count(),
                    'name' => $booking->screening->movie->title . ' Ticket',
                ],
                [
                    'id' => 'service-fee',
                    'price' => 5000,
                    'quantity' => 1,
                    'name' => 'Service Fee',
                ],
            ],
        ];

        try {
            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);

            // Cek apakah sudah ada payment pending untuk booking ini
            $existingPayment = Payment::where('booking_id', $booking->id)
                ->where('transaction_status', 'pending')
                ->latest()
                ->first();

            if ($existingPayment) {
                // Jika ingin reset waktu expired, update expired_at
                $existingPayment->update([
                    'payment_expired_at' => now()->addMinutes(5),
                ]);
                $payment = $existingPayment;
            } else {
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'order_id' => $params['transaction_details']['order_id'],
                    'payment_type' => 'midtrans',
                    'transaction_status' => 'pending',
                    'gross_amount' => $booking->total_price + 5000,
                    'currency' => 'IDR',
                    'payment_details' => $params,
                    'payment_expired_at' => now()->addMinutes(5),
                ]);
            }

            return view('payment.create', compact('booking', 'snapToken'));
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $paymentType = $notification->payment_type;

            Log::info('Midtrans Notification:', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'raw_notification' => $notification
            ]);

            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                throw new \Exception('Payment not found: ' . $orderId);
            }

            $booking = $payment->booking;

            if (!$booking) {
                throw new \Exception('Booking not found for payment: ' . $orderId);
            }

            // Tambahkan pengecekan status booking
            if ($booking->status === 'expired') {
                Log::warning('Payment received for expired booking: ' . $orderId);
                return response()->json(['message' => 'Booking already expired.'], 400);
            }

            $payment->update([
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'payment_details' => (array) $notification
            ]);

            // Update booking status based on transaction status
            $statusMap = [
                'capture' => 'paid',      // For credit card payment
                'settlement' => 'paid',    // For bank transfer, etc
                'pending' => 'pending',
                'deny' => 'failed',
                'expire' => 'expired',
                'cancel' => 'cancelled',
            ];

            if (isset($statusMap[$transactionStatus])) {
                $booking->update(['status' => $statusMap[$transactionStatus]]);
                // Kirim email tiket jika status menjadi paid
                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($booking->user->email)->send(new \App\Mail\TicketPaidMail($booking));
                    } catch (\Exception $mailEx) {
                        \Illuminate\Support\Facades\Log::error('Gagal mengirim email tiket: ' . $mailEx->getMessage());
                    }
                }
            }

            return response()->json(['message' => 'Notification processed successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
