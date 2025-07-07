<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\StudioObserver;
use App\Models\Studio;
use App\Observers\SeatObserver;
use App\Models\Seat;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Studio::observe(StudioObserver::class);
        Seat::observe(SeatObserver::class);
    }
}
