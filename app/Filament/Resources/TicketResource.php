<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Manajemen Tiket';

    protected static ?string $navigationLabel = 'Verifikasi Tiket';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ticket_code')
                    ->label('Kode Tiket')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_code')
                    ->label('Kode Tiket')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode tiket berhasil disalin')
                    ->copyMessageDuration(1500),
                TextColumn::make('booking.user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('booking.screening.movie.title')
                    ->label('Film')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('seat')
                    ->label('Kursi')
                    ->formatStateUsing(fn($record) => $record->seat ? $record->seat->row_letter . $record->seat->seat_number : '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('booking.screening.studio.name')
                    ->label('Studio')
                    ->sortable(),
                TextColumn::make('booking.screening.start_time')
                    ->label('Jadwal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('is_used')
                    ->label('Status')
                    ->badge()
                    ->color(fn(bool $state): string => match ($state) {
                        true => 'success',
                        false => 'gray',
                    })
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Sudah Digunakan' : 'Belum Digunakan'),
                TextColumn::make('used_at')
                    ->label('Waktu Penggunaan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Tiket')
                    ->options([
                        '0' => 'Belum Digunakan',
                        '1' => 'Sudah Digunakan',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            '0' => $query->where('is_used', false),
                            '1' => $query->where('is_used', true),
                            default => $query,
                        };
                    }),
                Filter::make('today_screenings')
                    ->label('Jadwal Hari Ini')
                    ->query(
                        fn(Builder $query): Builder => $query
                            ->whereHas('booking.screening', function ($q) {
                                $q->whereDate('start_time', today());
                            })
                    ),
                Filter::make('upcoming_screenings')
                    ->label('Jadwal Mendatang')
                    ->query(
                        fn(Builder $query): Builder => $query
                            ->whereHas('booking.screening', function ($q) {
                                $q->where('start_time', '>', now());
                            })
                    ),
                Filter::make('past_screenings')
                    ->label('Jadwal Terlewat')
                    ->query(
                        fn(Builder $query): Builder => $query
                            ->whereHas('booking.screening', function ($q) {
                                $q->where('start_time', '<', now()->subHours(3));
                            })
                    ),
            ])
            ->actions([
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Tiket')
                    ->modalDescription('Apakah Anda yakin ingin memverifikasi tiket ini?')
                    ->modalSubmitActionLabel('Ya, Verifikasi')
                    ->modalCancelActionLabel('Batal')
                    ->visible(
                        fn(Ticket $record): bool =>
                        !$record->is_used &&
                        $record->booking->status === 'paid'
                    )
                    ->action(function (Ticket $record): void {
                        try {
                            // Gunakan API verifikasi yang sama
                            $response = \Illuminate\Support\Facades\Http::post('/admin/tickets/verify', [
                                'ticket_code' => $record->ticket_code
                            ]);

                            $data = $response->json();

                            if ($data['status'] === 'success') {
                                // Refresh record untuk mendapatkan data terbaru
                                $record->refresh();

                                \Filament\Notifications\Notification::make()
                                    ->title('✅ ' . $data['message'])
                                    ->body("Film: {$data['data']['movie']} | Kursi: {$data['data']['seat']} | Studio: {$data['data']['studio']}")
                                    ->success()
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->title('❌ Verifikasi Gagal')
                                    ->body($data['message'])
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('❌ Terjadi Kesalahan')
                                ->body('Gagal memverifikasi tiket. Silakan coba lagi.')
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('view_details')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn(Ticket $record): string => route('filament.admin.resources.tickets.index', ['tableSearch' => $record->ticket_code]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Action::make('bulk_verify')
                        ->label('Verifikasi Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Tiket Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin memverifikasi semua tiket terpilih?')
                        ->modalSubmitActionLabel('Ya, Verifikasi Semua')
                        ->modalCancelActionLabel('Batal')
                        ->action(function (Collection $records): void {
                            $verifiedCount = 0;
                            $invalidCount = 0;
                            $errorMessages = [];

                            foreach ($records as $record) {
                                if (!$record->is_used && $record->booking->status === 'paid') {
                                    try {
                                        $response = \Illuminate\Support\Facades\Http::post('/admin/tickets/verify', [
                                            'ticket_code' => $record->ticket_code
                                        ]);

                                        $data = $response->json();

                                        if ($data['status'] === 'success') {
                                            $verifiedCount++;
                                        } else {
                                            $invalidCount++;
                                            $errorMessages[] = "Tiket {$record->ticket_code}: {$data['message']}";
                                        }
                                    } catch (\Exception $e) {
                                        $invalidCount++;
                                        $errorMessages[] = "Tiket {$record->ticket_code}: Gagal terhubung ke server";
                                    }
                                } else {
                                    $invalidCount++;
                                    $errorMessages[] = "Tiket {$record->ticket_code}: " .
                                        ($record->is_used ? 'Sudah digunakan' : 'Booking belum dibayar');
                                }
                            }

                            if ($verifiedCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title("✅ Berhasil memverifikasi {$verifiedCount} tiket")
                                    ->success()
                                    ->send();
                            }

                            if ($invalidCount > 0) {
                                $errorBody = count($errorMessages) <= 3
                                    ? implode("\n", $errorMessages)
                                    : implode("\n", array_slice($errorMessages, 0, 3)) . "\n...dan " . (count($errorMessages) - 3) . " tiket lainnya";

                                \Filament\Notifications\Notification::make()
                                    ->title("❌ {$invalidCount} tiket tidak dapat diverifikasi")
                                    ->body($errorBody)
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchable()
            ->searchPlaceholder('Cari berdasarkan kode tiket, nama pengguna, atau judul film...');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['booking.user', 'booking.screening.movie', 'booking.screening.studio', 'seat']);
    }
}
