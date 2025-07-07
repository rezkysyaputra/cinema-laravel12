<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'screening_id',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];


    /**
     * Relasi many-to-one dengan model User.
     * Setiap pemesanan dibuat oleh satu pengguna.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi many-to-one dengan model Screening.
     * Setiap pemesanan terkait dengan satu jadwal tayang.
     */
    public function screening()
    {
        return $this->belongsTo(Screening::class, 'screening_id');
    }

    /**
     * Relasi one-to-many dengan model Ticket.
     * Satu pemesanan dapat memiliki banyak tiket (untuk banyak tempat duduk).
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'booking_id');
    }

    /**
     * Relasi many-to-many dengan model Seat melalui model Ticket.
     * Satu pemesanan dapat memiliki banyak tempat duduk.
     */
    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'tickets', 'booking_id', 'seat_id');
    }

    /**
     * Relasi one-to-many dengan model Payment.
     * Satu pemesanan dapat memiliki banyak catatan pembayaran (untuk percobaan pembayaran).
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }
}
