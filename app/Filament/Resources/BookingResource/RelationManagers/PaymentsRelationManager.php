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

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->default(fn(RelationManager $livewire) => 'BOOK-' . $livewire->ownerRecord->id . '-' . Str::random(5)) // Contoh Order ID
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('midtrans_transaction_id')
                    ->maxLength(255)
                    ->nullable()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('gross_amount')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->maxLength(10)
                    ->default(fn(RelationManager $livewire) => $livewire->ownerRecord->total_price), // Default dari total_price booking
                Forms\Components\Select::make('payment_type')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'bank_transfer' => 'Bank Transfer',
                        'gopay' => 'GoPay',
                        'qris' => 'QRIS',
                        'other' => 'Lain-lain',
                    ])
                    ->required(),
                Forms\Components\Select::make('transaction_status')
                    ->options([
                        'pending' => 'Pending',
                        'settlement' => 'Settlement (Berhasil)',
                        'expire' => 'Expired',
                        'cancel' => 'Dibatalkan',
                        'deny' => 'Ditolak',
                        'refund' => 'Refund',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\DateTimePicker::make('transaction_time')
                    ->required()
                    ->default(now()),
                Forms\Components\Textarea::make('response_json')
                    ->label('Midtrans JSON Response')
                    ->columnSpan('full')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_id')
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('midtrans_transaction_id')
                    ->label('Midtrans ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_amount')
                    ->sortable()
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('payment_type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_status')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'settlement' => 'success',
                        'expire', 'cancel', 'deny' => 'danger',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('transaction_time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_status')
                    ->options([
                        'pending' => 'Pending',
                        'settlement' => 'Settlement (Berhasil)',
                        'expire' => 'Expired',
                        'cancel' => 'Dibatalkan',
                        'deny' => 'Ditolak',
                        'refund' => 'Refund',
                    ]),
                Tables\Filters\SelectFilter::make('payment_type')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'bank_transfer' => 'Bank Transfer',
                        'gopay' => 'GoPay',
                        'qris' => 'QRIS',
                        'other' => 'Lain-lain',
                    ]),
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
