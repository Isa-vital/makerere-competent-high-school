<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryMilestoneResource\Pages;
use App\Models\HistoryMilestone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HistoryMilestoneResource extends Resource
{
    protected static ?string $model = HistoryMilestone::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 65;
    protected static ?string $modelLabel = 'History Milestone';
    protected static ?string $pluralModelLabel = 'School History Timeline';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('year')->required()->maxLength(10),
                Forms\Components\TextInput::make('title')->required()->maxLength(150),
                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('year')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $s) => $s === 'active' ? 'success' : 'gray'),
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
            'index'  => Pages\ListHistoryMilestones::route('/'),
            'create' => Pages\CreateHistoryMilestone::route('/create'),
            'edit'   => Pages\EditHistoryMilestone::route('/{record}/edit'),
        ];
    }
}
