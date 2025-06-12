<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'synopsis',
        'genre',
        'duration',
        'poster_path',
        'trailer_url',
        'release_date',
    ];

    /**
     * Relasi one-to-many dengan model Screening.
     * Satu film dapat memiliki banyak jadwal tayang.
     */
    public function screenings()
    {
        return $this->hasMany(Screening::class, 'movie_id');
    }

    /**
     * Get the poster URL for the movie.
     */
    public function getPosterUrlAttribute()
    {
        if ($this->poster_path) {
            return asset('storage/' . $this->poster_path);
        }
        return asset('images/sample_poster.jpg');
    }
}
