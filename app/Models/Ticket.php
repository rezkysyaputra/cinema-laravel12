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
        'is_used',
        'used_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
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

    /**
     * Relasi ke payments melalui booking.
     */
    public function payments()
    {
        return $this->booking ? $this->booking->payments() : collect();
    }
}
