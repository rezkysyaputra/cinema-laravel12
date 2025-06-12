<?php

namespace App\Filament\Resources\StudioResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeatsRelationManager extends RelationManager
{
    protected static string $relationship = 'seats';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('row_letter')
                    ->required()
                    ->maxLength(1)
                    ->helperText('Contoh: A, B, C'),
                Forms\Components\TextInput::make('seat_number')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->helperText('Contoh: 1, 2, 3'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('seat_number')
            ->columns([
                Tables\Columns\TextColumn::make('row_letter')
                    ->sortable(),
                Tables\Columns\TextColumn::make('seat_number')
                    ->sortable(),
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
