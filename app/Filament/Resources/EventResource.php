<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('title')->required()->maxLength(255),
                    Forms\Components\Textarea::make('description')->rows(4)->columnSpanFull(),
                    Forms\Components\DatePicker::make('event_date')->required()->native(false),
                    Forms\Components\Select::make('category')
                        ->options([
                            'academic' => 'Academic', 'sports' => 'Sports',
                            'cultural' => 'Cultural', 'parents' => 'Parents Meeting',
                            'religious' => 'Religious', 'other' => 'Other',
                        ]),
                    Forms\Components\TimePicker::make('start_time'),
                    Forms\Components\TimePicker::make('end_time'),
                    Forms\Components\TextInput::make('location')->maxLength(255),
                    Forms\Components\FileUpload::make('featured_image')
                        ->image()
                        ->disk('public')
                        ->directory('events')
                        ->imageEditor(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'upcoming' => 'Upcoming', 'ongoing' => 'Ongoing',
                            'completed' => 'Completed', 'cancelled' => 'Cancelled',
                        ])
                        ->default('upcoming')
                        ->required(),
                    Forms\Components\Toggle::make('registration_required'),
                    Forms\Components\TextInput::make('max_participants')->numeric(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')->disk('public')->square()->size(50),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('event_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('location')->limit(30),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'upcoming' => 'info', 'ongoing' => 'success',
                        'completed' => 'gray', 'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('event_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'upcoming' => 'Upcoming', 'ongoing' => 'Ongoing',
                        'completed' => 'Completed', 'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit'   => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
