<?php

namespace App\Filament\Resources\StatutoryDeductionResource\Pages;

use App\Filament\Resources\StatutoryDeductionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatutoryDeduction extends EditRecord
{
    protected static string $resource = StatutoryDeductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
