<?php

namespace App\Filament\Resources\DeductionTypeResource\Pages;

use App\Filament\Resources\DeductionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeductionType extends EditRecord
{
    protected static string $resource = DeductionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
