<?php

namespace App\Filament\Resources\StatutoryDeductionResource\Pages;

use App\Filament\Resources\StatutoryDeductionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatutoryDeductions extends ListRecords
{
    protected static string $resource = StatutoryDeductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
