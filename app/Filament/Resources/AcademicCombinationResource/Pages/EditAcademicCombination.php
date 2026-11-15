<?php

namespace App\Filament\Resources\AcademicCombinationResource\Pages;

use App\Filament\Resources\AcademicCombinationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcademicCombination extends EditRecord
{
    protected static string $resource = AcademicCombinationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
