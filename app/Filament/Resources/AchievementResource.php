<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchievementResource\Pages;
use App\Models\Achievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 55;
    protected static ?string $modelLabel = 'Achievement';
    protected static ?string $pluralModelLabel = 'Achievements';

    public static function categoryOptions(): array
    {
        return [
            'school'          => 'General school achievement',
            'academic_results'=> 'Academic exam results',
            'alumni_giveback' => 'Alumni / MACOSA give-back',
        ];
    }

    public static function colorOptions(): array
    {
        return [
            'primary' => 'Primary (blue)',
            'success' => 'Success (green)',
            'warning' => 'Warning (yellow)',
            'info'    => 'Info (light blue)',
            'danger'  => 'Danger (red)',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(150),
                Forms\Components\TextInput::make('value')->maxLength(100)
                    ->placeholder('e.g., 65%, #1, 95%'),
                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                Forms\Components\TextInput::make('icon')->maxLength(100)->placeholder('fa-trophy'),
                Forms\Components\Select::make('color')->options(self::colorOptions())
                    ->default('primary')->required()->native(false),
                Forms\Components\Select::make('category')->options(self::categoryOptions())
                    ->default('school')->required()->native(false),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                    ->default('active')->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('value')->weight('bold'),
                Tables\Columns\TextColumn::make('category')->badge()
                    ->formatStateUsing(fn(string $s) => self::categoryOptions()[$s] ?? $s),
                Tables\Columns\TextColumn::make('color')->badge()
                    ->color(fn(string $s) => $s),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $s) => $s === 'active' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options(self::categoryOptions()),
                Tables\Filters\SelectFilter::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive']),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit'   => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}
