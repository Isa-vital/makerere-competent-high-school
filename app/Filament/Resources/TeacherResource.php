<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'People';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('full_name')->required()->maxLength(100),
                Forms\Components\TextInput::make('email')->email()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\TextInput::make('employee_id')->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('qualification'),
                Forms\Components\TextInput::make('specialization'),
                Forms\Components\Textarea::make('subjects_taught')->rows(2)->columnSpanFull(),
                Forms\Components\TextInput::make('experience_years')->numeric(),
                Forms\Components\DatePicker::make('hire_date'),
                Forms\Components\FileUpload::make('profile_photo')
                    ->image()
                    ->disk('public')
                    ->directory('teachers')
                    ->imageEditor(),
                Forms\Components\Textarea::make('bio')->rows(3)->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'retired' => 'Retired'])
                    ->default('active')->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')->disk('public')->circular(),
                Tables\Columns\TextColumn::make('full_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('specialization'),
                Tables\Columns\TextColumn::make('experience_years')->suffix(' yrs'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'active' => 'success', 'inactive' => 'gray', 'retired' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Active', 'inactive' => 'Inactive', 'retired' => 'Retired',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit'   => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
