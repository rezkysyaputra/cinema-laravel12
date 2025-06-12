<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'studio_id',
        'seat_number',
        'row_letter',
    ];

    /**
     * Relasi many-to-one dengan model Studio.
     * Setiap tempat duduk berada di satu studio.
     */
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * Relasi many-to-many dengan model Booking melalui model Ticket.
     * Satu tempat duduk dapat dipesan dalam banyak pemesanan (untuk jadwal berbeda).
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'tickets', 'seat_id', 'booking_id');
    }

    /**
     * Relasi one-to-many dengan model Ticket.
     * Satu tempat duduk dapat memiliki banyak tiket (untuk jadwal berbeda).
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'seat_id');
    }
}
