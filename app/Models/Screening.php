<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'studio_id',
        'start_time',
        'price',
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    /**
     * Relasi many-to-one dengan model Film.
     * Setiap jadwal tayang terkait dengan satu film.
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    /**
     * Relasi many-to-one dengan model Studio.
     * Setiap jadwal tayang berada di satu studio.
     */
    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studio_id');
    }

    /**
     * Relasi one-to-many dengan model Booking.
     * Satu jadwal tayang dapat memiliki banyak pemesanan.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'screening_id');
    }
}
