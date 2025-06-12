<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_code',
        'booking_id',
        'seat_id',
        'price',
    ];

    /**
     * Relasi many-to-one dengan model Booking.
     * Setiap tiket terkait dengan satu pemesanan.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Relasi many-to-one dengan model Seat.
     * Setiap tiket terkait dengan satu tempat duduk.
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }
}
