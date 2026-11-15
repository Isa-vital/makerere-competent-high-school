<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'Photo';

    protected static ?string $pluralModelLabel = 'Photos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image_path')
                            ->image()
                            ->disk('public')
                            ->directory('gallery')
                            ->imageEditor()
                            ->required()
                            ->maxSize(8192)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category')
                            ->options([
                                'academics'  => 'Academics',
                                'sports'     => 'Sports',
                                'events'     => 'Events',
                                'facilities' => 'Facilities',
                                'students'   => 'Student Life',
                                'other'      => 'Other',
                            ])
                            ->required()
                            ->default('other'),
                        Forms\Components\TextInput::make('alt_text')
                            ->maxLength(255),
                        Forms\Components\TagsInput::make('tags')->separator(','),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Select::make('status')
                            ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                            ->default('active')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->disk('public')
                    ->square()
                    ->size(60),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => $state === 'active' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'academics' => 'Academics', 'sports' => 'Sports',
                        'events' => 'Events', 'facilities' => 'Facilities',
                        'students' => 'Student Life', 'other' => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive']),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit'   => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
