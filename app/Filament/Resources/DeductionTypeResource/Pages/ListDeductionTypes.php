<?php

namespace App\Filament\Resources\DeductionTypeResource\Pages;

use App\Filament\Resources\DeductionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeductionTypes extends ListRecords
{
    protected static string $resource = DeductionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
