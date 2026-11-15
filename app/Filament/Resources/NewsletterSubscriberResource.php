<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationGroup = 'Submissions';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'Newsletter Subscriber';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\Select::make('status')
                ->options(['active' => 'Active', 'unsubscribed' => 'Unsubscribed'])
                ->default('active')->required(),
            Forms\Components\DateTimePicker::make('subscribed_at')->default(now()),
            Forms\Components\DateTimePicker::make('unsubscribed_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $state) => $state === 'active' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('subscribed_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('unsubscribed_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('subscribed_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Active', 'unsubscribed' => 'Unsubscribed',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNewsletterSubscribers::route('/'),
            'create' => Pages\CreateNewsletterSubscriber::route('/create'),
            'edit'   => Pages\EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }
}
