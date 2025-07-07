<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the movies.
     */
    public function index(Request $request)
    {
        $now = now();

        $filter = $request->input('filter', 'all'); // Default to 'all'
        $search = $request->input('search');

        $moviesQuery = Movie::query();

        if ($filter === 'now_showing') {
            $moviesQuery->whereHas('screenings', function ($query) use ($now) {
                $query->where('start_time', '>=', $now->startOfDay());
            });
        } elseif ($filter === 'coming_soon') {
            $moviesQuery->doesntHave('screenings');
        }
        // 'all' => tidak ada filter screening

        if ($search) {
            $moviesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        $movies = $moviesQuery->with('screenings')
            ->latest('created_at')
            ->paginate(10);

        return view('movies.index', compact('movies'));
    }

    public function show(Movie $movie)
    {
        $movie->load(['screenings.studio']);
        return view('movies.show', compact('movie'));

    }
}
