<?php

namespace App\Filament\Resources\FeesStructureResource\Pages;

use App\Filament\Resources\FeesStructureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeesStructure extends EditRecord
{
    protected static string $resource = FeesStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
