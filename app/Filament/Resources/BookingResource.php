<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pemesanan';
    protected static ?string $modelLabel = 'Pemesanan';
    protected static ?string $pluralModelLabel = 'Pemesanan';
    protected static ?string $navigationGroup = 'Manajemen Tiket';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pemesanan')
                    ->description('Edit informasi pemesanan tiket')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('user_name')
                                    ->label('Pengguna')
                                    ->disabled()
                                    ->placeholder('Nama pengguna akan ditampilkan di sini')
                                    ->dehydrated(false),

                                TextInput::make('screening_info')
                                    ->label('Jadwal Tayang')
                                    ->disabled()
                                    ->placeholder('Informasi jadwal akan ditampilkan di sini')
                                    ->dehydrated(false),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('total_price')
                                    ->label('Total Harga')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->maxValue(10000000)
                                    ->placeholder('Total harga pemesanan')
                                    ->disabled(), // Disable karena harga tidak boleh diubah

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'Menunggu Pembayaran',
                                        'paid' => 'Sudah Dibayar',
                                        'failed' => 'Gagal',
                                        'cancelled' => 'Dibatalkan',
                                    ])
                                    ->required()
                                    ->placeholder('Pilih status'),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('screening.movie.title')
                    ->label('Film')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->formatStateUsing(fn($state, $record) => $record->screening?->movie?->title ?? 'Tidak Tersedia'),

                TextColumn::make('screening.studio.name')
                    ->label('Studio')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('screening.start_time')
                    ->label('Waktu Tayang')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'cancelled' => 'gray',
                        default => 'info',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Sudah Dibayar',
                        'failed' => 'Gagal',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($state),
                    }),

                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Pengguna')
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih pengguna'),

                SelectFilter::make('screening')
                    ->relationship('screening', 'id')
                    ->label('Jadwal Tayang')
                    ->getOptionLabelUsing(
                        fn($record) => $record->movie
                        ? "{$record->movie->title} ({$record->studio->name} - {$record->start_time->format('d M Y H:i')})"
                        : "Screening #{$record->id}"
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih jadwal tayang'),

                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TicketsRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'screening.movie', 'screening.studio']);
    }
}
