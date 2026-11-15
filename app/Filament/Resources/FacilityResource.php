<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 50;
    protected static ?string $modelLabel = 'Facility';
    protected static ?string $pluralModelLabel = 'Facilities';

    public static function categoryOptions(): array
    {
        return [
            'school'   => 'School facility (about/academics page)',
            'academic' => 'Academic facility',
            'sports'   => 'Sports facility',
            'boarding' => 'Boarding / accommodation',
            'support'  => 'Support service',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(150),
                Forms\Components\Select::make('category')->options(self::categoryOptions())
                    ->default('school')->required()->native(false),
                Forms\Components\TextInput::make('icon')->maxLength(100)
                    ->placeholder('fa-flask')
                    ->helperText('Font Awesome class without the leading "fas" (e.g. fa-flask, fa-book)'),
                Forms\Components\FileUpload::make('image')->image()->disk('public')
                    ->directory('facilities')->imageEditor()->maxSize(4096),
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
                Tables\Columns\ImageColumn::make('image')->disk('public'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->badge()
                    ->formatStateUsing(fn(string $s) => self::categoryOptions()[$s] ?? $s),
                Tables\Columns\IconColumn::make('icon')->icon('heroicon-o-sparkles')
                    ->color(fn($state) => $state ? 'primary' : 'gray'),
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
            'index'  => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit'   => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}
