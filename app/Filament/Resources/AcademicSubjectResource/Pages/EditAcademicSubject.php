<?php

namespace App\Filament\Resources\AcademicSubjectResource\Pages;

use App\Filament\Resources\AcademicSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcademicSubject extends EditRecord
{
    protected static string $resource = AcademicSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
