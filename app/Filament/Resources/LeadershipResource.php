<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadershipResource\Pages;
use App\Models\Leadership;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadershipResource extends Resource
{
    protected static ?string $model = Leadership::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'People';
    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'Leader / Officer';
    protected static ?string $pluralModelLabel = 'Leaders & Officers';

    public static function categoryOptions(): array
    {
        return [
            'school_leadership' => 'School Leadership (Director, Head Teacher…)',
            'school_team'       => 'School Team / Staff Profile',
            'macosa_message'    => 'MACOSA Leadership Message (Founder, President)',
            'macosa_exec'       => 'MACOSA Executive Committee',
            'dept_contact'      => 'Department / Office Contact',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identity')->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(100)->columnSpan(1),
                Forms\Components\TextInput::make('role')->maxLength(100)->columnSpan(1)
                    ->placeholder('e.g., Resident Director, Head Teacher, MACOSA President'),
                Forms\Components\Select::make('category')->options(self::categoryOptions())
                    ->required()->native(false)->columnSpan(1),
                Forms\Components\TextInput::make('extra_info')->columnSpan(1)
                    ->placeholder('e.g., GEA/SM Program, MACOSA Founder'),
                Forms\Components\FileUpload::make('photo')->image()->disk('public')
                    ->directory('leadership')->imageEditor()->maxSize(4096)->columnSpan(2),
            ])->columns(2),

            Forms\Components\Section::make('Bio / Message')->schema([
                Forms\Components\Textarea::make('bio')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('quote')->rows(4)->columnSpanFull()
                    ->helperText('Shown as a featured message/quote on the website'),
            ]),

            Forms\Components\Section::make('Contact (optional)')->schema([
                Forms\Components\TextInput::make('email')->email()->maxLength(150),
                Forms\Components\TextInput::make('phone')->tel()->maxLength(50),
            ])->columns(2),

            Forms\Components\Section::make('Display')->schema([
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
                Tables\Columns\ImageColumn::make('photo')->disk('public')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('role')->searchable(),
                Tables\Columns\TextColumn::make('category')->badge()
                    ->formatStateUsing(fn(string $state) => self::categoryOptions()[$state] ?? $state),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $state) => $state === 'active' ? 'success' : 'gray'),
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
            'index'  => Pages\ListLeaderships::route('/'),
            'create' => Pages\CreateLeadership::route('/create'),
            'edit'   => Pages\EditLeadership::route('/{record}/edit'),
        ];
    }
}
