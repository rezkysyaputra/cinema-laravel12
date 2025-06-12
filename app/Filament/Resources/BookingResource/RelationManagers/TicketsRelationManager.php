<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ticket_code')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->default(fn() => Str::random(10)) // Otomatis generate kode tiket
                    ->disabledOn('edit'), // Tidak bisa diubah setelah dibuat
                Forms\Components\Select::make('seat_id')
                    ->label('Kursi')
                    ->relationship(
                        'seat',
                        'seat_number',
                        fn(Builder $query, RelationManager $livewire) =>
                        $query->where('studio_id', $livewire->ownerRecord->screening->studio_id)
                    )
                    ->getOptionLabelUsing(fn($record) => "{$record->row_letter}{$record->seat_number}")
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->maxLength(10)
                    ->default(fn(RelationManager $livewire) => $livewire->ownerRecord->screening->price), // Default harga dari screening
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ticket_code')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seat.row_letter')
                    ->label('Baris Kursi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('seat.seat_number')
                    ->label('Nomor Kursi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
