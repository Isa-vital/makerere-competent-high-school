<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Submissions';

    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('subject', 'not like', 'Admission Inquiry - %');
    }

    protected static ?string $modelLabel = 'Contact Message';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->where('status', 'new')->count();

        return $count ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Message')->schema([
                Forms\Components\TextInput::make('name')->disabled(),
                Forms\Components\TextInput::make('email')->disabled(),
                Forms\Components\TextInput::make('phone')->disabled(),
                Forms\Components\TextInput::make('subject')->disabled(),
                Forms\Components\Textarea::make('message')->rows(8)->disabled()->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Workflow')->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'new'       => 'New',
                        'read'      => 'Read',
                        'replied'   => 'Replied',
                        'archived'  => 'Archived',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('replied_at'),
                Forms\Components\Select::make('replied_by')
                    ->relationship('repliedBy', 'name')
                    ->searchable(),
            ])->columns(3),

            Forms\Components\Section::make('Metadata')->schema([
                Forms\Components\TextInput::make('ip_address')->disabled(),
                Forms\Components\Textarea::make('user_agent')->disabled()->columnSpanFull(),
                Forms\Components\DateTimePicker::make('created_at')->disabled(),
            ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('subject')->limit(40)->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'new' => 'warning', 'read' => 'info',
                        'replied' => 'success', 'archived' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New', 'read' => 'Read',
                        'replied' => 'Replied', 'archived' => 'Archived',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('markReplied')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn($record) => $record->status !== 'replied')
                    ->action(fn($record) => $record->update([
                        'status' => 'replied',
                        'replied_at' => now(),
                        'replied_by' => auth()->id(),
                    ])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'edit'  => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
