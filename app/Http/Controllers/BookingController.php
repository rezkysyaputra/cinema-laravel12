<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Screening;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create(Screening $screening)
    {
        // Load the studio and movie for the Livewire component
        $screening->load(['studio', 'movie']);

        return view('booking.create', compact('screening'));
    }
}
