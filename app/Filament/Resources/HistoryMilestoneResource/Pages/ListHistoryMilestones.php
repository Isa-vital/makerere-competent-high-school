<?php

namespace App\Filament\Resources\HistoryMilestoneResource\Pages;

use App\Filament\Resources\HistoryMilestoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHistoryMilestones extends ListRecords
{
    protected static string $resource = HistoryMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
