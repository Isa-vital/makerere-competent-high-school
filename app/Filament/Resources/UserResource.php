<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 90;

    protected static ?string $modelLabel = 'Admin User';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('username')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn($state) => filled($state))
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->required(fn(string $operation) => $operation === 'create'),
                Forms\Components\Select::make('role')
                    ->options(['super_admin' => 'Super Admin', 'admin' => 'Admin'])
                    ->default('admin')
                    ->required(),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Permissions (Spatie roles)'),
                Forms\Components\Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                    ->default('active')
                    ->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('username')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('role')->badge(),
                Tables\Columns\TextColumn::make('roles.name')->badge()->separator(','),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $state) => $state === 'active' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('last_login')->dateTime()->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')->options([
                    'super_admin' => 'Super Admin', 'admin' => 'Admin',
                ]),
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Active', 'inactive' => 'Inactive',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
