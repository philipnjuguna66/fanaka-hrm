<?php

namespace App\Livewire;

use App\Filament\Resources\EmployeeResource;
use App\Models\EmployeeBenefit;
use App\Models\EmployeeDeduction;
use App\Models\IPayroll;
use App\Models\StatutoryDeduction;
use App\Services\PayrollService;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class PayrollPreview extends Component implements HasTable,HasForms,HasActions
{

    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public function render()
    {
        return view('livewire.payroll-preview');
    }

    public function table(Table $table): Table
    {

        $statutory =StatutoryDeduction::all()->map(function ($deduction){
            return TextColumn::make(str($deduction->name)->lower()->value());
        })->all();

        $deductions = EmployeeDeduction::query()->get()
            ->map(function ($deduction) {

                return TextColumn::make(str($deduction->name)->lower()->value());
            });
        $benefits = EmployeeBenefit::query()->get()
            ->map(function ($benefit) {

                return TextColumn::make(str($benefit->name)->lower()->value());
            });

        return $table
            ->query(IPayroll::query())
            ->columns([
                TextColumn::make('employee_name'),
                TextColumn::make('basic_pay'),
                TextColumn::make('gross_pay'),
                TextColumn::make('tax_allowable_deductions')->wrap(),
                TextColumn::make('taxable_income'),
                    ...$statutory,
                TextColumn::make('paye'),
                TextColumn::make('withholding_tax'),
                ...$benefits,
               ...$deductions,
                TextColumn::make('personal_relief'),
                TextColumn::make('insurance_relief'),
                TextColumn::make('net_payee')->label("PAYE"),
                TextColumn::make('net_pay'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('view')->url(fn(Model $row) => EmployeeResource::getUrl(name:'edit',parameters: ['record' => $row->employee_id]))
            ])
            ->bulkActions([
                // ...
            ])->striped();
    }
}
