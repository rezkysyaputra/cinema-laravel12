<?php

namespace App\Filament\Resources\StudioResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScreeningsRelationManager extends RelationManager
{
    protected static string $relationship = 'screenings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('movie_id')
                    ->label('Movie')
                    ->relationship('movie', 'title')
                    ->required()
                    ->hidden(fn(?string $operation) => $operation === 'edit' && $this->ownerRecord instanceof \App\Models\Film), // Sembunyikan jika di MovieResource
                Forms\Components\Select::make('studio_id')
                    ->label('Studio')
                    ->relationship('studio', 'name')
                    ->required()
                    ->hidden(fn(?string $operation) => $operation === 'edit' && $this->ownerRecord instanceof \App\Models\Studio), // Sembunyikan jika di StudioResource
                Forms\Components\DateTimePicker::make('start_time')
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->maxLength(10),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('start_time')
            ->columns([
                Tables\Columns\TextColumn::make('movie.title')
                    ->label('Movie')
                    ->searchable()
                    ->sortable()
                    ->hidden(fn($record) => $this->ownerRecord instanceof \App\Models\Film), // Sembunyikan jika di MovieResource
                Tables\Columns\TextColumn::make('studio.name')
                    ->label('Studio')
                    ->searchable()
                    ->sortable()
                    ->hidden(fn($record) => $this->ownerRecord instanceof \App\Models\Studio), // Sembunyikan jika di StudioResource
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime()
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
