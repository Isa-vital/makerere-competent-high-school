<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdmissionResource\Pages;
use App\Models\Admission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdmissionResource extends Resource
{
    protected static ?string $model = Admission::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?string $modelLabel = 'Application';

    protected static ?string $pluralModelLabel = 'Applications';

    protected static ?string $navigationGroup = 'Submissions';

    protected static ?int $navigationSort = 20;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Student')->schema([
                Forms\Components\TextInput::make('student_name')->required()->maxLength(255),
                Forms\Components\DatePicker::make('date_of_birth'),
                Forms\Components\Select::make('gender')->options([
                    'male' => 'Male', 'female' => 'Female',
                ]),
                Forms\Components\Select::make('class_applying')->options([
                    'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3', 'S4' => 'S4',
                    'S5' => 'S5', 'S6' => 'S6',
                ]),
                Forms\Components\TextInput::make('previous_school')->maxLength(255),
            ])->columns(2),

            Forms\Components\Section::make('Parent / Guardian')->schema([
                Forms\Components\TextInput::make('parent_name')->maxLength(255),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\Textarea::make('address')->rows(2)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Processing')->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'enrolled' => 'Enrolled',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\Toggle::make('application_fee_paid'),
                Forms\Components\Textarea::make('notes')->rows(3)->columnSpanFull(),
                Forms\Components\Select::make('processed_by')->relationship('processor', 'name')->searchable(),
                Forms\Components\DateTimePicker::make('processed_at'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('student_name')->searchable(),
                Tables\Columns\TextColumn::make('parent_name')->searchable(),
                Tables\Columns\TextColumn::make('class_applying')->badge(),
                Tables\Columns\TextColumn::make('email')->copyable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\IconColumn::make('application_fee_paid')->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'pending'  => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'enrolled' => 'info',
                        default    => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending', 'approved' => 'Approved',
                    'rejected' => 'Rejected', 'enrolled' => 'Enrolled',
                ]),
                Tables\Filters\SelectFilter::make('class_applying')->options([
                    'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3', 'S4' => 'S4',
                    'S5' => 'S5', 'S6' => 'S6',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmissions::route('/'),
            'edit'  => Pages\EditAdmission::route('/{record}/edit'),
        ];
    }
}
