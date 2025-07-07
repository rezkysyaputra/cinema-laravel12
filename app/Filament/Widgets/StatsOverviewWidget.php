<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Movie;
use App\Models\Studio;
use App\Models\Screening;
use App\Models\Booking;
use App\Models\Ticket;

class StatsOverviewWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Film', Movie::count()),
            Card::make('Total Studio', Studio::count()),
            Card::make('Total Jadwal Tayang', Screening::count()),
            Card::make('Total Booking', Booking::count()),
            Card::make('Total Tiket', Ticket::count()),
        ];
    }
}
