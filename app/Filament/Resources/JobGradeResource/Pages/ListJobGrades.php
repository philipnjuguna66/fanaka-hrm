<?php

namespace App\Filament\Resources\JobGradeResource\Pages;

use App\Filament\Resources\JobGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobGrades extends ListRecords
{
    protected static string $resource = JobGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
