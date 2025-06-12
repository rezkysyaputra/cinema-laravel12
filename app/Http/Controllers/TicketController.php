<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->bookings()
            ->with(['screening.movie', 'screening.studio'])
            ->latest()
            ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function show(Booking $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load(['screening.movie', 'screening.studio', 'seats']);

        return view('tickets.show', compact('ticket'));
    }
}
