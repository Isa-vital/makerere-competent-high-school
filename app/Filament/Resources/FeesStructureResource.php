<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeesStructureResource\Pages;
use App\Models\FeesStructure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeesStructureResource extends Resource
{
    protected static ?string $model = FeesStructure::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 45;
    protected static ?string $modelLabel = 'Fees Item';
    protected static ?string $pluralModelLabel = 'Fees Structure';

    public static function feeTypeOptions(): array
    {
        return [
            'boarding'         => 'Boarding (full)',
            'day'              => 'Day (full)',
            'boarding_bursary' => 'Boarding (bursary)',
            'day_bursary'      => 'Day (bursary)',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('class_level')->required()
                    ->placeholder('e.g., S.1 - S.3, S.4, S.5 - S.6'),
                Forms\Components\Select::make('fee_type')->options(self::feeTypeOptions())
                    ->required()->native(false),
                Forms\Components\TextInput::make('amount')->numeric()->required()->prefix('UGX'),
                Forms\Components\TextInput::make('amount_secondary')->maxLength(50)
                    ->placeholder('Optional second-amount: 203,000/=/300,000/=')
                    ->helperText('Used when bursary fees vary by class (e.g., 203k S.1-S.3 / 300k S.4-S.6)'),
                Forms\Components\TextInput::make('term')->maxLength(20)->placeholder('e.g., Term I'),
                Forms\Components\TextInput::make('year')->maxLength(10)->placeholder('e.g., 2026'),
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
                Tables\Columns\TextColumn::make('class_level')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('fee_type')->badge()
                    ->formatStateUsing(fn(string $s) => self::feeTypeOptions()[$s] ?? $s)
                    ->color(fn(string $s) => str_contains($s, 'bursary') ? 'warning' : 'primary'),
                Tables\Columns\TextColumn::make('amount')->money('UGX')->sortable(),
                Tables\Columns\TextColumn::make('amount_secondary'),
                Tables\Columns\TextColumn::make('term'),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $s) => $s === 'active' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('fee_type')->options(self::feeTypeOptions()),
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
            'index'  => Pages\ListFeesStructures::route('/'),
            'create' => Pages\CreateFeesStructure::route('/create'),
            'edit'   => Pages\EditFeesStructure::route('/{record}/edit'),
        ];
    }
}
