<?php

namespace App\Filament\Resources\DeductionTypeResource\Pages;

use App\Filament\Resources\DeductionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDeductionType extends ViewRecord
{
    protected static string $resource = DeductionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
