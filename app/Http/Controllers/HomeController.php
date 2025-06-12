<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $now = now();

        // Get movies that have screenings today
        $nowShowing = Movie::whereHas('screenings', function ($query) use ($now) {
            $query->where('start_time', '>=', $now->startOfDay())
                ->where('start_time', '<=', $now->copy()->endOfDay());
        })
            ->with([
                'screenings' => function ($query) use ($now) {
                    $query->where('start_time', '>=', $now->startOfDay())
                        ->where('start_time', '<=', $now->copy()->endOfDay());
                }
            ])
            ->latest()
            ->take(4)
            ->get();

        // Get movies that have screenings in the future (after today)
        $comingSoon = Movie::whereHas('screenings', function ($query) use ($now) {
            $query->where('start_time', '>', $now->endOfDay());
        })
            ->with([
                'screenings' => function ($query) use ($now) {
                    $query->where('start_time', '>', $now->endOfDay());
                }
            ])
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('nowShowing', 'comingSoon'));
    }
}
