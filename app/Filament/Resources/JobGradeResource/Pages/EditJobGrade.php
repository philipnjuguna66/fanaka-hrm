<?php

namespace App\Filament\Resources\JobGradeResource\Pages;

use App\Filament\Resources\JobGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobGrade extends EditRecord
{
    protected static string $resource = JobGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
