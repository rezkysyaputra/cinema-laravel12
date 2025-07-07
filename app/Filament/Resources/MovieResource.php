<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovieResource\Pages;
use App\Models\Movie;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class MovieResource extends Resource
{
    protected static ?string $model = Movie::class;
    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationLabel = 'Daftar Film';
    protected static ?string $modelLabel = 'Film';
    protected static ?string $pluralModelLabel = 'Film';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Manajemen Konten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        // Left Column - Main Info
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Informasi Film')
                                    ->description('Masukkan informasi dasar film')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Judul Film')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Masukkan judul film')
                                            ->live(onBlur: true),

                                        Textarea::make('synopsis')
                                            ->label('Sinopsis')
                                            ->required()
                                            ->rows(6)
                                            ->placeholder('Masukkan sinopsis film yang menarik')
                                            ->columnSpanFull(),
                                    ])->columns(2),

                                Section::make('Detail Film')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('genre')
                                                    ->label('Genre')
                                                    ->required()
                                                    ->options([
                                                        'Action' => 'Action',
                                                        'Adventure' => 'Adventure',
                                                        'Animation' => 'Animation',
                                                        'Comedy' => 'Comedy',
                                                        'Crime' => 'Crime',
                                                        'Documentary' => 'Documentary',
                                                        'Drama' => 'Drama',
                                                        'Family' => 'Family',
                                                        'Fantasy' => 'Fantasy',
                                                        'Horror' => 'Horror',
                                                        'Mystery' => 'Mystery',
                                                        'Romance' => 'Romance',
                                                        'Sci-Fi' => 'Sci-Fi',
                                                        'Thriller' => 'Thriller',
                                                        'War' => 'War',
                                                    ])
                                                    ->searchable()
                                                    ->placeholder('Pilih genre film'),

                                                TextInput::make('duration')
                                                    ->label('Durasi (menit)')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->maxValue(999)
                                                    ->placeholder('120')
                                                    ->suffix('menit'),

                                                DatePicker::make('release_date')
                                                    ->label('Tanggal Rilis')
                                                    ->required()
                                                    ->placeholder('Pilih tanggal rilis')
                                                    ->maxDate(now()->addYears(10)),

                                                TextInput::make('trailer_url')
                                                    ->label('URL Trailer')
                                                    ->url()
                                                    ->maxLength(255)
                                                    ->placeholder('https://youtube.com/watch?v=...')
                                                    ->helperText('Link YouTube trailer film'),
                                            ]),
                                    ]),
                            ]),

                        // Right Column - Media
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Media')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        FileUpload::make('poster_path')
                                            ->label('Poster Film')
                                            ->image()
                                            ->disk('cloudinary')
                                            ->directory('posters')
                                            ->visibility('public')
                                            ->maxSize(5120)
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('2:3')
                                            ->imageResizeTargetWidth('400')
                                            ->imageResizeTargetHeight('600')
                                            ->helperText('Rasio aspek 2:3, maksimal 5MB')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->previewable(true)
                                            ->downloadable(false)
                                            ->openable(false),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster_path')
                    ->label('Poster')
                    ->disk('cloudinary')
                    ->width(60)
                    ->height(90)
                    ->circular(false),

                TextColumn::make('title')
                    ->label('Judul Film')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->description(fn(Movie $record): string => Str::limit($record->synopsis, 80))
                    ->wrap(),

                TextColumn::make('genre')
                    ->label('Genre')
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('duration')
                    ->label('Durasi')
                    ->formatStateUsing(fn(int $state): string => "{$state} menit")
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('release_date')
                    ->label('Tanggal Rilis')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color(
                        fn(Movie $record): string =>
                        \Illuminate\Support\Carbon::parse($record->release_date)->isPast() ? 'success' : 'warning'
                    ),

                TextColumn::make('screenings_count')
                    ->label('Jadwal Tayang')
                    ->counts('screenings')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('release_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('genre')
                    ->label('Filter Genre')
                    ->options([
                        'Action' => 'Action',
                        'Adventure' => 'Adventure',
                        'Animation' => 'Animation',
                        'Comedy' => 'Comedy',
                        'Crime' => 'Crime',
                        'Documentary' => 'Documentary',
                        'Drama' => 'Drama',
                        'Family' => 'Family',
                        'Fantasy' => 'Fantasy',
                        'Horror' => 'Horror',
                        'Mystery' => 'Mystery',
                        'Romance' => 'Romance',
                        'Sci-Fi' => 'Sci-Fi',
                        'Thriller' => 'Thriller',
                        'War' => 'War',
                    ])
                    ->multiple()
                    ->searchable(),

                Tables\Filters\Filter::make('release_date')
                    ->label('Filter Tanggal Rilis')
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
                                fn(Builder $query, $date): Builder => $query->whereDate('release_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('release_date', '<=', $date),
                            );
                    }),

                Tables\Filters\Filter::make('has_trailer')
                    ->label('Hanya Film dengan Trailer')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('trailer_url')),

                Tables\Filters\Filter::make('has_poster')
                    ->label('Hanya Film dengan Poster')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('poster_path')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat Detail')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->icon('heroicon-o-pencil'),
                    Tables\Actions\Action::make('preview_trailer')
                        ->label('Preview Trailer')
                        ->icon('heroicon-o-play')
                        ->url(fn(Movie $record): string => $record->trailer_url ?? '#')
                        ->openUrlInNewTab()
                        ->visible(fn(Movie $record): bool => !empty($record->trailer_url)),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Aksi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih')
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-film')
            ->emptyStateHeading('Belum ada film')
            ->emptyStateDescription('Mulai dengan menambahkan film pertama Anda.')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Tambah Film')
                    ->url(route('filament.admin.resources.movies.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovies::route('/'),
            'create' => Pages\CreateMovie::route('/create'),
            'edit' => Pages\EditMovie::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['poster_path']) && is_array($data['poster_path'])) {
            $data['image_public_id'] = $data['poster_path']['public_id'] ?? null;
            $data['poster_path'] = $data['poster_path']['url'] ?? null;
        }
        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['poster_path']) && is_array($data['poster_path'])) {
            $data['image_public_id'] = $data['poster_path']['public_id'] ?? null;
            $data['poster_path'] = $data['poster_path']['url'] ?? null;
        }
        return $data;
    }
}
