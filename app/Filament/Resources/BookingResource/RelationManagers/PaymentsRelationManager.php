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
    protected static ?string $title = 'Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?string $pluralModelLabel = 'Pembayaran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->label('ID Pesanan')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->default(fn(RelationManager $livewire) => 'BOOK-' . $livewire->ownerRecord->id . '-' . strtoupper(Str::random(5)))
                    ->disabledOn('edit')
                    ->placeholder('ID pesanan akan dibuat otomatis'),

                Forms\Components\TextInput::make('midtrans_transaction_id')
                    ->label('ID Transaksi Midtrans')
                    ->maxLength(255)
                    ->nullable()
                    ->unique(ignoreRecord: true)
                    ->placeholder('ID transaksi dari Midtrans'),

                Forms\Components\TextInput::make('gross_amount')
                    ->label('Jumlah Pembayaran')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0.01)
                    ->maxValue(10000000)
                    ->default(fn(RelationManager $livewire) => $livewire->ownerRecord->total_price)
                    ->placeholder('Jumlah yang harus dibayar'),

                Forms\Components\Select::make('payment_type')
                    ->label('Metode Pembayaran')
                    ->options([
                        'credit_card' => 'Kartu Kredit',
                        'bank_transfer' => 'Transfer Bank',
                        'gopay' => 'GoPay',
                        'qris' => 'QRIS',
                        'other' => 'Lain-lain',
                    ])
                    ->required()
                    ->placeholder('Pilih metode pembayaran'),

                Forms\Components\Select::make('transaction_status')
                    ->label('Status Transaksi')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'settlement' => 'Berhasil',
                        'expire' => 'Kadaluarsa',
                        'cancel' => 'Dibatalkan',
                        'deny' => 'Ditolak',
                        'refund' => 'Dikembalikan',
                    ])
                    ->required()
                    ->default('pending')
                    ->placeholder('Status transaksi'),

                Forms\Components\DateTimePicker::make('transaction_time')
                    ->label('Waktu Transaksi')
                    ->required()
                    ->default(now())
                    ->placeholder('Waktu transaksi dilakukan'),

                Forms\Components\Textarea::make('response_json')
                    ->label('Response JSON Midtrans')
                    ->columnSpan('full')
                    ->nullable()
                    ->placeholder('Response JSON dari Midtrans (opsional)'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_id')
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('ID Pesanan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('ID pesanan berhasil disalin'),

                Tables\Columns\TextColumn::make('midtrans_transaction_id')
                    ->label('ID Transaksi Midtrans')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('gross_amount')
                    ->label('Jumlah Pembayaran')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Metode Pembayaran')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'credit_card' => 'Kartu Kredit',
                        'bank_transfer' => 'Transfer Bank',
                        'gopay' => 'GoPay',
                        'qris' => 'QRIS',
                        'other' => 'Lain-lain',
                        default => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('transaction_status')
                    ->label('Status Transaksi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'settlement' => 'success',
                        'expire', 'cancel', 'deny' => 'danger',
                        'refund' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu Pembayaran',
                        'settlement' => 'Berhasil',
                        'expire' => 'Kadaluarsa',
                        'cancel' => 'Dibatalkan',
                        'deny' => 'Ditolak',
                        'refund' => 'Dikembalikan',
                        default => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('transaction_time')
                    ->label('Waktu Transaksi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_status')
                    ->label('Status Transaksi')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'settlement' => 'Berhasil',
                        'expire' => 'Kadaluarsa',
                        'cancel' => 'Dibatalkan',
                        'deny' => 'Ditolak',
                        'refund' => 'Dikembalikan',
                    ]),
                Tables\Filters\SelectFilter::make('payment_type')
                    ->label('Metode Pembayaran')
                    ->options([
                        'credit_card' => 'Kartu Kredit',
                        'bank_transfer' => 'Transfer Bank',
                        'gopay' => 'GoPay',
                        'qris' => 'QRIS',
                        'other' => 'Lain-lain',
                    ]),
            ])
            ->headerActions([
                // Tidak ada tombol tambah karena pembayaran dibuat otomatis saat booking
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
