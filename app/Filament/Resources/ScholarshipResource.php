<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScholarshipResource\Pages;
use App\Models\Scholarship;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScholarshipResource extends Resource
{
    protected static ?string $model = Scholarship::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 42;
    protected static ?string $modelLabel = 'Scholarship';
    protected static ?string $pluralModelLabel = 'Scholarships';

    public static function colorOptions(): array
    {
        return [
            'primary' => 'Primary (blue)',
            'success' => 'Success (green)',
            'warning' => 'Warning (yellow)',
            'info'    => 'Info',
            'danger'  => 'Danger',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(150),
                Forms\Components\TextInput::make('icon')->maxLength(100)->placeholder('fa-award'),
                Forms\Components\Textarea::make('description')->rows(3)->required()->columnSpanFull(),
                Forms\Components\Textarea::make('eligibility')->rows(2)->columnSpanFull(),
                Forms\Components\Select::make('color')->options(self::colorOptions())
                    ->default('primary')->native(false),
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
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')->limit(60)->wrap(),
                Tables\Columns\TextColumn::make('color')->badge()->color(fn(string $state) => $state),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $state) => $state === 'active' ? 'success' : 'gray'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive']),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListScholarships::route('/'),
            'create' => Pages\CreateScholarship::route('/create'),
            'edit'   => Pages\EditScholarship::route('/{record}/edit'),
        ];
    }
}
