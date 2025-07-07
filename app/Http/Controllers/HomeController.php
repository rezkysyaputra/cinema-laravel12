<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $now = now();

        // Now Showing: Film yang punya screening hari ini atau yang akan datang
        $nowShowing = Movie::whereHas('screenings', function ($query) use ($now) {
            $query->where('start_time', '>=', $now->startOfDay());
        })
            ->with([
                'screenings' => function ($query) use ($now) {
                    $query->where('start_time', '>=', $now->startOfDay());
                }
            ])
            ->latest()
            ->take(4)
            ->get();

        // Coming Soon: Film yang belum punya screening (tanpa cek release_date)
        $comingSoon = Movie::doesntHave('screenings')->latest()->take(4)->get();

        return view('home', compact('nowShowing', 'comingSoon'));
    }
}
