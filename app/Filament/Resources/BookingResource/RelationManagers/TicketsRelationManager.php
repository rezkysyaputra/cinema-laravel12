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
    protected static ?string $title = 'Tiket';
    protected static ?string $modelLabel = 'Tiket';
    protected static ?string $pluralModelLabel = 'Tiket';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ticket_code')
                    ->label('Kode Tiket')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->default(fn() => 'TIX-' . strtoupper(Str::random(8)))
                    ->disabledOn('edit')
                    ->placeholder('Kode tiket akan dibuat otomatis'),

                Forms\Components\Select::make('seat_id')
                    ->label('Kursi')
                    ->relationship(
                        'seat',
                        'code',
                        fn(Builder $query, RelationManager $livewire) =>
                        $query->where('studio_id', $livewire->ownerRecord->screening->studio_id)
                    )
                    ->getOptionLabelUsing(fn($record) => $record->row_letter . $record->seat_number)
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0.01)
                    ->maxValue(1000000)
                    ->default(fn(RelationManager $livewire) => $livewire->ownerRecord->screening->price)
                    ->placeholder('Harga tiket'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ticket_code')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_code')
                    ->label('Kode Tiket')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode tiket berhasil disalin'),

                Tables\Columns\TextColumn::make('seat')
                    ->label('Kursi')
                    ->formatStateUsing(fn($record) => $record->seat ? $record->seat->row_letter . $record->seat->seat_number : '-')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_used')
                    ->label('Status')
                    ->badge()
                    ->color(fn(bool $state): string => match ($state) {
                        true => 'success',
                        false => 'gray',
                    })
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Sudah Digunakan' : 'Belum Digunakan'),

                Tables\Columns\TextColumn::make('used_at')
                    ->label('Waktu Penggunaan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tidak ada tombol tambah karena tiket dibuat otomatis saat booking
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
}
