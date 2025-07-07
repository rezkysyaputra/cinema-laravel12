<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Screening;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingForm extends Component
{
    public $screening;
    public $selectedSeats = [];
    public $bookedSeatIds = [];
    public $studioId;

    public function mount(Screening $screening)
    {
        $this->screening = $screening;
        $this->studioId = $screening->studio_id;
        $this->loadBookedSeats();
    }

    public function loadBookedSeats()
    {
        // Get booked seat IDs for this screening
        $this->bookedSeatIds = Booking::where('screening_id', $this->screening->id)
            ->with('tickets.seat')
            ->get()
            ->pluck('tickets.*.seat.id')
            ->flatten()
            ->toArray();
    }

    public function toggleSeat($seatId)
    {
        if (in_array($seatId, $this->bookedSeatIds)) {
            return; // Seat is already booked
        }

        if (in_array($seatId, $this->selectedSeats)) {
            $this->selectedSeats = array_diff($this->selectedSeats, [$seatId]);
        } else {
            $this->selectedSeats[] = $seatId;
        }
    }

    public function getSeatsByRowProperty()
    {
        $allSeats = Seat::where('studio_id', $this->studioId)->get();
        return $allSeats->groupBy('row');
    }

    public function getRowsProperty()
    {
        return range(1, $this->screening->studio->row);
    }

    public function getTotalPriceProperty()
    {
        return count($this->selectedSeats) * $this->screening->price;
    }

    public function getFormattedTotalPriceProperty()
    {
        return 'Rp ' . number_format($this->totalPrice, 0, ',', '.');
    }

    public function getFormattedPricePerSeatProperty()
    {
        return 'Rp ' . number_format($this->screening->price, 0, ',', '.');
    }

    public function proceedToPayment()
    {
        if (empty($this->selectedSeats)) {
            session()->flash('error', 'Silakan pilih kursi terlebih dahulu.');
            return;
        }

        try {
            DB::beginTransaction();

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'screening_id' => $this->screening->id,
                'total_price' => $this->totalPrice,
                'status' => 'pending'
            ]);

            // Create tickets for each selected seat
            foreach ($this->selectedSeats as $seatId) {
                Ticket::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'price' => $this->screening->price,
                    'ticket_code' => 'TIX-' . strtoupper(Str::random(8))
                ]);
            }

            DB::commit();

            // Redirect to payment page with the booking
            return redirect()->route('payment.create', $booking)
                ->with('success', 'Kursi berhasil dipilih! Silakan lanjutkan ke pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memproses pemesanan. Silakan coba lagi.');
            return;
        }
    }

    public function render()
    {
        return view('livewire.booking-form');
    }
}
