<?php

namespace Database\Seeders;

use App\Models\Screening;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScreeningSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua movie ID
        $allMovieIds = \App\Models\Movie::pluck('id')->toArray();
        $totalMovies = count($allMovieIds);
        $screenedCount = (int) round($totalMovies * 0.6); // 60% film diberi screening

        // Acak dan ambil 60% movie ID untuk Now Showing (diberi screening)
        $nowShowingMovieIds = collect($allMovieIds)->shuffle()->take($screenedCount)->toArray();

        // Sisanya otomatis jadi Coming Soon (tidak diberi screening)

        // Buat screening untuk testing
        foreach ($nowShowingMovieIds as $index => $movieId) {
            // Untuk film pertama, buat beberapa screening yang 30 menit dari sekarang (untuk testing verifikasi)
            if ($index === 0) {
                // Buat 3 screening yang 30 menit dari sekarang
                for ($i = 0; $i < 3; $i++) {
                    $testTime = Carbon::now()->addMinutes(20 + ($i * 5)); // 30, 35, 40 menit dari sekarang
                    \App\Models\Screening::factory()->create([
                        'movie_id' => $movieId,
                        'start_time' => $testTime,
                    ]);
                }
            }

            // Buat screening normal untuk masa depan (5-8 per film)
            \App\Models\Screening::factory(rand(5, 8))->create([
                'movie_id' => $movieId,
            ]);
        }
    }
}
