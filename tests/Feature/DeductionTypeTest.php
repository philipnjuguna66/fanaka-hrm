<?php

use function Pest\Livewire\livewire;

test('it can render deduction type resource page', function () {
    $this->get(\App\Filament\Resources\DeductionResource::getUrl('index'))->assertSuccessful();
});


test('it can list deduction types', function () {

    $this->seed(\Database\Seeders\DeductionTypeSeeder::class);

    livewire(\App\Filament\Resources\DeductionTypeResource\Pages\ListDeductionTypes::class)
        ->assertCanSeeTableRecords(\App\Models\DeductionType::query()->get());

});


test('it can render deduction type create page', function () {
    $this->get(\App\Filament\Resources\DeductionResource::getUrl('create'))->assertSuccessful();
});

