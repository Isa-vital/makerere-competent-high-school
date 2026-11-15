<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicProgramResource\Pages;
use App\Models\AcademicProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AcademicProgramResource extends Resource
{
    protected static ?string $model = AcademicProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 40;

    protected static ?string $modelLabel = 'Academic Program';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('program_name')->required()->maxLength(100),
                Forms\Components\TextInput::make('program_code')->required()->unique(ignoreRecord: true)->maxLength(20),
                Forms\Components\Select::make('level')
                    ->options(['O-Level' => 'O-Level', 'A-Level' => 'A-Level'])
                    ->required(),
                Forms\Components\TextInput::make('duration')->maxLength(50)->placeholder('e.g., 4 Years'),
                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('subjects')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('requirements')->rows(3)->columnSpanFull(),
                Forms\Components\TextInput::make('fees')->numeric()->prefix('UGX'),
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
                Tables\Columns\TextColumn::make('program_code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('program_name')->searchable(),
                Tables\Columns\TextColumn::make('level')->badge(),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('fees')->money('UGX'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => $state === 'active' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')->options(['O-Level' => 'O-Level', 'A-Level' => 'A-Level']),
                Tables\Filters\SelectFilter::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive']),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAcademicPrograms::route('/'),
            'create' => Pages\CreateAcademicProgram::route('/create'),
            'edit'   => Pages\EditAcademicProgram::route('/{record}/edit'),
        ];
    }
}
