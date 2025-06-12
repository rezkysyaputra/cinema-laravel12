<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScreeningResource\Pages;
use App\Filament\Resources\ScreeningResource\RelationManagers;
use App\Models\Screening;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScreeningResource extends Resource
{
    protected static ?string $model = Screening::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('movie_id')
                    ->label('Movie')
                    ->relationship('movie', 'title')
                    ->required(),
                Select::make('studio_id')
                    ->label('Studio')
                    ->relationship('studio', 'name')
                    ->required(),
                DateTimePicker::make('start_time')
                    ->required(),
                TextInput::make('price')
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
                TextColumn::make('movie.title')
                    ->label('Movie')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('studio.name')
                    ->label('Studio')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('price')
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
                Tables\Filters\SelectFilter::make('movie_id')
                    ->relationship('movie', 'title'),
                Tables\Filters\SelectFilter::make('studio')
                    ->relationship('studio', 'name'),
                Tables\Filters\Filter::make('start_time')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Tayang Mulai Dari'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Tayang Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->where('start_time', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->where('start_time', '<=', $date),
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
            RelationManagers\BookingsRelationManager::class,

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
