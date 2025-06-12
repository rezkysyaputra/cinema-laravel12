<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        return view('payment.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        // Process payment logic here
        $booking->update(['status' => 'paid']);
        return redirect()->route('booking.show', $booking)->with('success', 'Payment successful!');
    }
}
