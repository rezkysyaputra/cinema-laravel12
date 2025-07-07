<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Pemesanan'),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit Pemesanan';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Memastikan relasi dimuat sebelum form diisi
        $booking = $this->getRecord()->load(['user', 'screening.movie', 'screening.studio']);

        // Tambahkan data untuk ditampilkan di form
        $data['user_name'] = $booking->user ? $booking->user->name : 'Tidak diketahui';
        $data['screening_info'] = $booking->screening && $booking->screening->movie
            ? "{$booking->screening->movie->title} ({$booking->screening->studio->name} - {$booking->screening->start_time->format('d M Y H:i')})"
            : 'Tidak diketahui';

        return $data;
    }
}
