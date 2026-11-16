<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicCombinationResource\Pages;
use App\Models\AcademicCombination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AcademicCombinationResource extends Resource
{
    protected static ?string $model = AcademicCombination::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 37;
    protected static ?string $modelLabel = 'A-Level Combination';
    protected static ?string $pluralModelLabel = 'A-Level Combinations';

    public static function facultyOptions(): array
    {
        return [
            'sciences'   => 'Sciences',
            'arts'       => 'Arts / Humanities',
            'commercial' => 'Commercial',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('code')->required()->maxLength(20)
                    ->placeholder('e.g., PCM, PCB, MEG, HEG'),
                Forms\Components\TextInput::make('name')->maxLength(100)
                    ->placeholder('Optional descriptive name'),
                Forms\Components\TextInput::make('subjects')->maxLength(255)
                    ->placeholder('Physics, Chemistry, Mathematics')->columnSpanFull(),
                Forms\Components\Select::make('faculty')->options(self::facultyOptions())
                    ->required()->native(false),
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
                Tables\Columns\TextColumn::make('code')->weight('bold')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('subjects')->wrap(),
                Tables\Columns\TextColumn::make('faculty')->badge()
                    ->formatStateUsing(fn(string $state) => self::facultyOptions()[$state] ?? $state)
                    ->color(fn(string $state) => match ($state) {
                        'sciences' => 'success', 'arts' => 'warning', 'commercial' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $state) => $state === 'active' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('faculty')->options(self::facultyOptions()),
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
            'index'  => Pages\ListAcademicCombinations::route('/'),
            'create' => Pages\CreateAcademicCombination::route('/create'),
            'edit'   => Pages\EditAcademicCombination::route('/{record}/edit'),
        ];
    }
}
