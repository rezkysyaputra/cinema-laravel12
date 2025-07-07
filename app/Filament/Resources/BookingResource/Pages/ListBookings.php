<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada tombol create karena booking dibuat otomatis saat user melakukan pemesanan
        ];
    }

    public function getTitle(): string
    {
        return 'Daftar Pemesanan';
    }
}
