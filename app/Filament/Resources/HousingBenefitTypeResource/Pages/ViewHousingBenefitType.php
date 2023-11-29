<?php

namespace App\Filament\Resources\HousingBenefitTypeResource\Pages;

use App\Filament\Resources\HousingBenefitTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHousingBenefitType extends ViewRecord
{
    protected static string $resource = HousingBenefitTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
