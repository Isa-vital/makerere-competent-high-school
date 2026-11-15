<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicSubjectResource\Pages;
use App\Models\AcademicSubject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AcademicSubjectResource extends Resource
{
    protected static ?string $model = AcademicSubject::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 35;
    protected static ?string $modelLabel = 'Subject';
    protected static ?string $pluralModelLabel = 'Academic Subjects';

    public static function categoryOptions(): array
    {
        return [
            'core'       => 'Core (compulsory)',
            'elective'   => 'Elective',
            'sciences'   => 'Sciences',
            'humanities' => 'Humanities / Arts',
            'commercial' => 'Commercial',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(100),
                Forms\Components\TextInput::make('code')->maxLength(20),
                Forms\Components\Select::make('level')
                    ->options(['O-Level' => 'O-Level', 'A-Level' => 'A-Level'])
                    ->required()->native(false),
                Forms\Components\Select::make('category')->options(self::categoryOptions())
                    ->default('core')->native(false),
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
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('level')->badge()
                    ->color(fn(string $s) => $s === 'O-Level' ? 'info' : 'warning'),
                Tables\Columns\TextColumn::make('category')->badge()
                    ->formatStateUsing(fn(string $s) => self::categoryOptions()[$s] ?? $s),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $s) => $s === 'active' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')->options(['O-Level' => 'O-Level', 'A-Level' => 'A-Level']),
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
            'index'  => Pages\ListAcademicSubjects::route('/'),
            'create' => Pages\CreateAcademicSubject::route('/create'),
            'edit'   => Pages\EditAcademicSubject::route('/{record}/edit'),
        ];
    }
}
