<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected static string $view = 'filament.resources.ticket-resource.pages.list-tickets';

    protected function getHeaderActions(): array
    {
        return [
            // Tidak perlu action create karena tiket dibuat otomatis saat booking
        ];
    }

    public function getTitle(): string
    {
        return 'Verifikasi Tiket';
    }
}
