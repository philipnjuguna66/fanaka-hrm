<?php

namespace App\Filament\Resources\HousingBenefitTypeResource\Pages;

use App\Filament\Resources\HousingBenefitTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHousingBenefitType extends EditRecord
{
    protected static string $resource = HousingBenefitTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
