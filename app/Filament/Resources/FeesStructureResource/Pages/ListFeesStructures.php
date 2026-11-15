<?php

namespace App\Filament\Resources\FeesStructureResource\Pages;

use App\Filament\Resources\FeesStructureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeesStructures extends ListRecords
{
    protected static string $resource = FeesStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
