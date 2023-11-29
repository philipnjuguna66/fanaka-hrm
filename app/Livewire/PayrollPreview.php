<?php

namespace App\Livewire;

use App\Filament\Resources\EmployeeResource;
use App\Models\IPayroll;
use App\Models\StatutoryDeduction;
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
            return TextColumn::make(str($deduction->name)->lower()->value()) ->money(currency: 'kes');
        })->all();


        return $table
            ->query(IPayroll::query())
            ->columns([
                TextColumn::make('employee_name')->money(currency: 'kes'),
                TextColumn::make('basic_pay')->money(currency: 'kes'),
                TextColumn::make('gross_pay')->money(currency: 'kes'),
                TextColumn::make('tax_allowable_deductions')->wrap()->money(currency: 'kes'),
                TextColumn::make('taxable_income')->money(currency: 'kes'),
                    ...$statutory,
                TextColumn::make('paye')->money(currency: 'kes'),
                TextColumn::make('personal_relief')->money(currency: 'kes'),
                TextColumn::make('insurance_relief')->money(currency: 'kes'),
                TextColumn::make('net_payee')->label("PAYE")->money(currency: 'kes'),
                TextColumn::make('net_pay')->money(currency: 'kes'),
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
