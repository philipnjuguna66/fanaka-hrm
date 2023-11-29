<?php

namespace App\Filament\Resources\HousingBenefitTypeResource\Pages;

use App\Filament\Resources\HousingBenefitTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHousingBenefitTypes extends ListRecords
{
    protected static string $resource = HousingBenefitTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
