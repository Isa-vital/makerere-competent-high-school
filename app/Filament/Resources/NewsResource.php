<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Article')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, Forms\Set $set, ?string $operation) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Textarea::make('excerpt')
                            ->rows(3)
                            ->maxLength(500),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Meta')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->image()
                            ->disk('public')
                            ->directory('news')
                            ->imageEditor()
                            ->maxSize(4096),
                        Forms\Components\Select::make('category')
                            ->options([
                                'general'     => 'General',
                                'academic'    => 'Academic',
                                'sports'      => 'Sports',
                                'events'      => 'Events',
                                'achievement' => 'Achievement',
                                'announcement' => 'Announcement',
                            ])
                            ->searchable(),
                        Forms\Components\TagsInput::make('tags')
                            ->separator(','),
                    ])->columns(2),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft'     => 'Draft',
                                'published' => 'Published',
                                'archived'  => 'Archived',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->default(now()),
                        Forms\Components\Toggle::make('featured')
                            ->helperText('Show this article on the homepage hero strip.'),
                        Forms\Components\Select::make('author_id')
                            ->relationship('author', 'name')
                            ->default(fn() => auth()->id())
                            ->searchable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->disk('public')
                    ->square()
                    ->size(50),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        'archived'  => 'gray',
                        default     => 'gray',
                    }),
                Tables\Columns\IconColumn::make('featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('views')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'general'  => 'General',
                        'academic' => 'Academic',
                        'sports'   => 'Sports',
                        'events'   => 'Events',
                        'achievement' => 'Achievement',
                        'announcement' => 'Announcement',
                    ]),
                Tables\Filters\TernaryFilter::make('featured'),
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
            'index'  => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit'   => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
