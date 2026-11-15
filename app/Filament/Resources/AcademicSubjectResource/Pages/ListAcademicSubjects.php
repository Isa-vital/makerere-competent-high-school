<?php

namespace App\Filament\Resources\AcademicSubjectResource\Pages;

use App\Filament\Resources\AcademicSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcademicSubjects extends ListRecords
{
    protected static string $resource = AcademicSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
