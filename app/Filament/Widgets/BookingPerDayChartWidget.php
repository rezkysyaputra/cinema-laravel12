<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class BookingPerDayChartWidget extends LineChartWidget
{
    protected static ?string $heading = 'Booking 7 Hari Terakhir';

    protected function getData(): array
    {
        $dates = collect(range(0, 6))->map(function ($i) {
            return Carbon::today()->subDays(6 - $i)->format('Y-m-d');
        });

        $bookings = Booking::query()
            ->where('created_at', '>=', Carbon::today()->subDays(6))
            ->get()
            ->groupBy(fn($b) => $b->created_at->format('Y-m-d'));

        $data = $dates->map(fn($date) => isset($bookings[$date]) ? $bookings[$date]->count() : 0);

        return [
            'datasets' => [
                [
                    'label' => 'Booking',
                    'data' => $data->toArray(),
                    'borderColor' => '#f59e42',
                    'backgroundColor' => 'rgba(245,158,66,0.2)',
                ],
            ],
            'labels' => $dates->map(fn($d) => date('d M', strtotime($d)))->toArray(),
        ];
    }
}
