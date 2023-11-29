<?php

namespace App\Filament\Resources\StatutoryDeductionResource\Pages;

use App\Filament\Resources\StatutoryDeductionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStatutoryDeduction extends ViewRecord
{
    protected static string $resource = StatutoryDeductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
