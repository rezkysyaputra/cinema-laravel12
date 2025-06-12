<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('screening_id')
                    ->label('Jadwal Tayang')
                    ->relationship('screening', 'id', fn(Builder $query) => $query->with(['movie', 'studio']))
                    ->getOptionLabelUsing(fn($record) => $record->movie ? "{$record->movie->title} ({$record->studio->name} - {$record->start_time->format('d M Y H:i')})" : "Screening #{$record->id}")
                    ->required()
                    ->searchable(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->maxLength(10),
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
                    ->label('Movie')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $record->screening?->movie?->title ?? 'N/A'),
                TextColumn::make('screening.studio.name')
                    ->label('Studio')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('screening.start_time')
                    ->label('Showtime')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('total_price')
                    ->sortable()
                    ->money('IDR'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name'),
                SelectFilter::make('screening')
                    ->relationship('screening', 'id') // Use the primary key or a string attribute
                    ->getOptionLabelUsing(fn($record) => "{$record->movie->title} ({$record->studio->name} - {$record->start_time->format('d M Y H:i')})"),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
