<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Screening $screening)
    {
        // Load the studio with its seats and the movie
        $screening->load(['studio.seats', 'movie']);

        // Get all seats for this studio
        $allSeats = $screening->studio->seats;

        // Get booked seat IDs for this screening
        $bookedSeatIds = $screening->bookings()
            ->with('seats')
            ->get()
            ->pluck('seats.*.id')
            ->flatten()
            ->toArray();

        return view('booking.create', compact('screening', 'allSeats', 'bookedSeatIds'));
    }

    public function store(Request $request, Screening $screening)
    {
        $request->validate([
            'selected_seats' => 'required|array|min:1',
            'selected_seats.*' => 'exists:seats,id'
        ]);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'screening_id' => $screening->id,
            'total_price' => $screening->price * count($request->selected_seats)
        ]);

        // Attach selected seats to the booking with price
        $seatPrices = array_fill_keys($request->selected_seats, ['price' => $screening->price]);
        $booking->seats()->attach($seatPrices);

        // Redirect to payment page
        return redirect()->route('payment.create', $booking)
            ->with('success', 'Kursi berhasil dipilih! Silakan lanjutkan ke pembayaran.');
    }
}
