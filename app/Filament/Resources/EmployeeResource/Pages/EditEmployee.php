<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Enums\EmploymentTerms;
use App\Filament\Resources\EmployeeResource;
use App\Models\BusinessUnit;
use App\Models\Department;
use App\Models\JobGrade;
use App\Models\JobTitle;
use App\Models\Region;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{

//    use EditRecord\Concerns\HasWizard;


    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
//        dd($this->record->salaryDetail);

        return $data;
    }

    protected function validateFormAndUpdateRecordAndCallHooks(): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('afterValidate');

        $data = $this->mutateFormDataBeforeSave($data);


        $this->callHook('beforeSave');

        $this->handleRecordUpdate($this->getRecord(), $data);

        $this->callHook('afterSave');
    }

    protected function getSteps() :array
    {

        return [

            Forms\Components\Wizard\Step::make('Personal Details')->schema(CreateEmployee::personalDetails()),
            Forms\Components\Wizard\Step::make('Salary Details')->schema(CreateEmployee::salaryDetails()),
            Forms\Components\Wizard\Step::make('Hr Details')->schema(CreateEmployee::hrDetails()),
            Forms\Components\Wizard\Step::make('Contact Details')->schema(CreateEmployee::contactDetailsForm()),
        ];

    }
}
