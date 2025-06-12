<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'midtrans_transaction_id',
        'order_id',
        'gross_amount',
        'payment_type',
        'transaction_status',
        'transaction_time',
        'response_json'
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
