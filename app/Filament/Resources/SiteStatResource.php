<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteStatResource\Pages;
use App\Models\SiteStat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteStatResource extends Resource
{
    protected static ?string $model = SiteStat::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 70;
    protected static ?string $modelLabel = 'Site Statistic';
    protected static ?string $pluralModelLabel = 'Site Statistics';

    public static function categoryOptions(): array
    {
        return [
            'homepage' => 'Homepage hero stats',
            'about'    => 'About-page stats',
            'academic' => 'Academic results',
            'alumni'   => 'Alumni / MACOSA stats',
            'sacco'    => 'MACOSA SACCO stats',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('stat_key')->required()->maxLength(100)
                    ->unique(ignoreRecord: true)
                    ->helperText('lowercase_with_underscores, used in code references'),
                Forms\Components\TextInput::make('label')->required()->maxLength(150)
                    ->placeholder('e.g., Students, Graduation Rate'),
                Forms\Components\TextInput::make('value')->required()->maxLength(50)
                    ->placeholder('e.g., 1200, 95'),
                Forms\Components\TextInput::make('suffix')->maxLength(20)
                    ->placeholder('e.g., +, %, M+'),
                Forms\Components\TextInput::make('icon')->maxLength(100)
                    ->placeholder('fa-users'),
                Forms\Components\Select::make('category')->options(self::categoryOptions())
                    ->default('homepage')->required()->native(false),
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
                Tables\Columns\TextColumn::make('label')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('value')->weight('bold'),
                Tables\Columns\TextColumn::make('suffix'),
                Tables\Columns\TextColumn::make('category')->badge()
                    ->formatStateUsing(fn(string $state) => self::categoryOptions()[$state] ?? $state),
                Tables\Columns\TextColumn::make('stat_key')->color('gray')->size('xs'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $state) => $state === 'active' ? 'success' : 'gray'),
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
            'index'  => Pages\ListSiteStats::route('/'),
            'create' => Pages\CreateSiteStat::route('/create'),
            'edit'   => Pages\EditSiteStat::route('/{record}/edit'),
        ];
    }
}
