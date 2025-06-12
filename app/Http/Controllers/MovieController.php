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

        // Get movies that have screenings today or in the future
        $nowShowingMovies = Movie::whereHas('screenings', function ($query) use ($now) {
            $query->where('start_time', '>=', $now->startOfDay())
                ->where('start_time', '<=', $now->copy()->endOfDay());
        })
            ->when($request->search, function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('genre', 'like', "%{$request->search}%");
            })
            ->with([
                'screenings' => function ($query) use ($now) {
                    $query->where('start_time', '>=', $now->startOfDay())
                        ->where('start_time', '<=', $now->copy()->endOfDay());
                }
            ])
            ->latest()
            ->take(8)
            ->get();

        // Get movies that have screenings in the future (after today)
        $comingSoonMovies = Movie::whereHas('screenings', function ($query) use ($now) {
            $query->where('start_time', '>', $now->endOfDay());
        })
            ->when($request->search, function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('genre', 'like', "%{$request->search}%");
            })
            ->with([
                'screenings' => function ($query) use ($now) {
                    $query->where('start_time', '>', $now->endOfDay());
                }
            ])
            ->latest()
            ->take(8)
            ->get();

        return view('movies.index', compact('nowShowingMovies', 'comingSoonMovies'));
    }

    public function show(Movie $movie)
    {
        $movie->load(['screenings.studio']);
        return view('movies.show', compact('movie'));

    }
}
