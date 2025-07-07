<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            'Dune: Part Two',
            'Godzilla x Kong: The New Empire',
            'Kung Fu Panda 4',
            'Inside Out 2',
            'Civil War',
            'Furiosa: A Mad Max Saga',
            'Kingdom of the Planet of the Apes',
            'The Fall Guy',
            'Challengers',
            'Bad Boys: Ride or Die',
            'Marco',
            'The Garfield Movie',
            'IF',
            'The Beekeeper',
            'Monkey Man',
            'Ghostbusters: Frozen Empire',
            'Atlas',
            'Back to Black',
            'The Ministry of Ungentlemanly Warfare',
            'Immaculate',
            'Abigail',
            'Boy Kills World',
            'Tarot',
            'The Watchers',
            'Afraid',
            'Cash Out',
            'Carjackers',
            'How to Train Your Dragon',
        ];

        foreach ($titles as $title) {
            $response = Http::get('https://www.omdbapi.com/', [
                't' => $title,
                'apikey' => env('OMDB_API_KEY'),
            ]);
            $data = $response->json();

            if (!empty($data['Title']) && ($data['Response'] ?? 'False') === 'True' && !empty($data['Poster']) && $data['Poster'] !== 'N/A') {
                Movie::updateOrCreate(
                    ['title' => $data['Title']],
                    [
                        'synopsis' => $data['Plot'] ?? '-',
                        'duration' => isset($data['Runtime']) ? (int) filter_var($data['Runtime'], FILTER_SANITIZE_NUMBER_INT) : 120,
                        'genre' => $data['Genre'] ?? '-',
                        'poster_path' => $data['Poster'],
                        'trailer_url' => null, // OMDb tidak ada trailer
                        'release_date' => isset($data['Released']) ? date('Y-m-d', strtotime($data['Released'])) : null,
                    ]
                );
            }
        }
    }
}
