<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'order_id',
        'payment_type',
        'transaction_status',
        'transaction_id',
        'status_message',
        'gross_amount',
        'currency',
        'payment_details',
        'paid_at',
        'status'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'gross_amount' => 'decimal:2',
        'status' => 'string'
    ];

    /**
     * Relasi many-to-one dengan model Booking.
     * Setiap pembayaran terkait dengan satu pemesanan.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
