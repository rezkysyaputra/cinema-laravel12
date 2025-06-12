<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
    ];

    /**
     * Relasi one-to-many dengan model Screening.
     * Satu studio dapat memiliki banyak jadwal tayang.
     */
    public function screenings()
    {
        return $this->hasMany(Screening::class, 'studio_id');
    }

    /**
     * Relasi one-to-many dengan model Seat.
     * Satu studio memiliki banyak tempat duduk.
     */
    public function seats()
    {
        return $this->hasMany(Seat::class, 'studio_id');
    }
}
