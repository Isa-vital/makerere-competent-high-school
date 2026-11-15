<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlumniResource\Pages;
use App\Models\Alumni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AlumniResource extends Resource
{
    protected static ?string $model = Alumni::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'People';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Alumnus';

    protected static ?string $pluralModelLabel = 'Alumni';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Personal')->schema([
                Forms\Components\TextInput::make('full_name')->required(),
                Forms\Components\TextInput::make('maiden_name'),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\FileUpload::make('profile_photo')
                    ->image()
                    ->disk('public')
                    ->directory('alumni')
                    ->imageEditor(),
                Forms\Components\TextInput::make('location')->placeholder('e.g., Kampala, Uganda'),
            ])->columns(2),

            Forms\Components\Section::make('Academic')->schema([
                Forms\Components\TextInput::make('graduation_year')->numeric()->required(),
                Forms\Components\Select::make('class_level')
                    ->options(['S4' => 'S4 (O-Level)', 'S6' => 'S6 (A-Level)'])
                    ->required(),
                Forms\Components\TextInput::make('course_studied'),
            ])->columns(3),

            Forms\Components\Section::make('Professional')->schema([
                Forms\Components\TextInput::make('current_profession'),
                Forms\Components\TextInput::make('current_employer'),
                Forms\Components\TextInput::make('linkedin_profile')->url(),
                Forms\Components\Textarea::make('bio')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('achievements')->rows(4)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Engagement')->schema([
                Forms\Components\Toggle::make('willing_to_mentor'),
                Forms\Components\Toggle::make('willing_to_speak'),
                Forms\Components\Toggle::make('willing_to_donate'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')->disk('public')->circular(),
                Tables\Columns\TextColumn::make('full_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('graduation_year')->sortable(),
                Tables\Columns\TextColumn::make('class_level')->badge(),
                Tables\Columns\TextColumn::make('current_profession')->limit(30),
                Tables\Columns\TextColumn::make('location')->limit(25),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'approved' => 'success', 'pending' => 'warning',
                        'rejected' => 'danger', default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected',
                ]),
                Tables\Filters\SelectFilter::make('class_level')->options(['S4' => 'S4', 'S6' => 'S6']),
                Tables\Filters\TernaryFilter::make('willing_to_mentor'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status !== 'approved')
                    ->action(fn($record) => $record->update(['status' => 'approved'])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAlumni::route('/'),
            'create' => Pages\CreateAlumni::route('/create'),
            'edit'   => Pages\EditAlumni::route('/{record}/edit'),
        ];
    }
}
