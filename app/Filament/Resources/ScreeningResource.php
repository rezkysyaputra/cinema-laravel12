<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScreeningResource\Pages;
use App\Filament\Resources\ScreeningResource\RelationManagers;
use App\Models\Screening;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class ScreeningResource extends Resource
{
    protected static ?string $model = Screening::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Jadwal Tayang';
    protected static ?string $modelLabel = 'Jadwal';
    protected static ?string $pluralModelLabel = 'Jadwal';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Jadwal Tayang')
                    ->description('Masukkan informasi jadwal pemutaran film')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('movie_id')
                                    ->label('Film')
                                    ->options(function () {
                                        return \App\Models\Movie::pluck('title', 'id')->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Pilih film'),

                                Select::make('studio_id')
                                    ->label('Studio')
                                    ->options(function () {
                                        return \App\Models\Studio::pluck('name', 'id')->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Pilih studio'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('start_time')
                                    ->label('Waktu Mulai')
                                    ->required()
                                    ->format('d/m/Y H:i')
                                    ->displayFormat('d/m/Y H:i')
                                    ->native(false)
                                    ->seconds(false)
                                    ->placeholder('Pilih waktu mulai tayang')
                                    ->default(now()->addHour())
                                    ->minDate(now()),

                                TextInput::make('price')
                                    ->label('Harga Tiket')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->maxValue(1000000)
                                    ->placeholder('Masukkan harga tiket'),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('movie.title')
                    ->label('Film')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Screening $record): string => $record->movie->duration . ' menit'),

                TextColumn::make('studio.name')
                    ->label('Studio')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('start_time')
                    ->label('Waktu Tayang')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->color(
                        fn(Screening $record): string =>
                        Carbon::parse($record->start_time)->isPast() ? 'danger' :
                        (Carbon::parse($record->start_time)->isToday() ? 'warning' : 'success')
                    )
                    ->description(
                        fn(Screening $record): string =>
                        Carbon::parse($record->start_time)->isPast() ? 'Sudah Tayang' :
                        (Carbon::parse($record->start_time)->isToday() ? 'Hari Ini' : Carbon::parse($record->start_time)->diffForHumans())
                    ),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->color('success'),

                TextColumn::make('bookings_count')
                    ->label('Total Booking')
                    ->counts('bookings')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('start_time')
            ->filters([
                Tables\Filters\SelectFilter::make('movie')
                    ->relationship('movie', 'title')
                    ->label('Film')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('studio')
                    ->relationship('studio', 'name')
                    ->label('Studio')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('start_time')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('start_time', '>=', Carbon::parse($date)),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('start_time', '<=', Carbon::parse($date)),
                            );
                    }),

                Tables\Filters\Filter::make('status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'upcoming' => 'Akan Datang',
                                'today' => 'Hari Ini',
                                'past' => 'Sudah Tayang',
                            ])
                            ->placeholder('Semua Status'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['status'] === 'upcoming',
                                fn(Builder $query): Builder => $query->where('start_time', '>', Carbon::now()),
                            )
                            ->when(
                                $data['status'] === 'today',
                                fn(Builder $query): Builder => $query->whereDate('start_time', Carbon::today()),
                            )
                            ->when(
                                $data['status'] === 'past',
                                fn(Builder $query): Builder => $query->where('start_time', '<', Carbon::now()),
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
            // Removed BookingsRelationManager
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScreenings::route('/'),
            'create' => Pages\CreateScreening::route('/create'),
            'edit' => Pages\EditScreening::route('/{record}/edit'),
        ];
    }
}
