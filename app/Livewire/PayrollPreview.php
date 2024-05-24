<?php

namespace App\Livewire;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Resources\EmployeeResource;

use App\Models\Benefit;
use App\Models\Deduction;
use App\Models\IPayroll;
use App\Models\TempPayroll;
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
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class PayrollPreview extends Component implements HasTable, HasForms, HasActions
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


        $temps = TempPayroll::query()->get();

        $columns = [];

        $grossAndBasic = [];

        $benefitColumns = [];


        $nssf = [];

        foreach ($temps as $payroll) {

            $basicPay = floatval($payroll->temp['basic_pay']);
            $grossPay = floatval($payroll->temp);

            $grossAndBasic = [
                TextColumn::make('basic_pay')->numeric(2)->default($grossPay)->searchable(),

            ];

            foreach ($payroll->temp as $index => $value) {

                //  TextColumn::make("nssf")->label('Employee N.S.S.F')->searchable()->numeric(2),
                //  TextColumn::make("tax_allowable_deductions")->label('Employer N.S.S.F')->searchable()->numeric(2),


                if (!in_array($index, ["net_pay", 'paye', 'employee_id', "gross_pay", "net_payee", 'car_benefits', "nssf", "tax_allowable_deductions", 'housing_benefits', 'personal_relief', 'insurance_relief','housing_relief'])) {

                    $columns[$index] = TextColumn::make($index)->size(TextColumn\TextColumnSize::ExtraSmall)->searchable()->default(floatval($value))->numeric(2);

                } else {
                    $nssf["nhif"] = TextColumn::make("nhif")->size(TextColumn\TextColumnSize::ExtraSmall)->label('N.H.I.F')->searchable()->numeric(2);
                    $nssf["nssf"] = TextColumn::make("nssf")->size(TextColumn\TextColumnSize::ExtraSmall)->label('Employee N.S.S.F')->searchable()->numeric(2);
                    $nssf["tax_allowable_deductions"] = TextColumn::make("tax_allowable_deductions")->size(TextColumn\TextColumnSize::ExtraSmall)->label('Employer N.S.S.F')->searchable()->numeric(2);

                    $columns['net_pay'] = TextColumn::make('net_pay')
                        ->default($payroll->temp['net_pay'])
                        ->numeric(2);
                    $columns['net_payee'] = TextColumn::make('net_payee')->size(TextColumn\TextColumnSize::ExtraSmall)
                        ->label('Payee')->default($payroll->temp['net_payee'])->size(TextColumn\TextColumnSize::ExtraSmall)->numeric(2);;
                    $columns['insurance_relief'] = TextColumn::make('insurance_relief')->size(TextColumn\TextColumnSize::ExtraSmall)->numeric(2)->default($payroll->temp['insurance_relief']);
                    $columns['personal_relief'] = TextColumn::make('personal_relief')->size(TextColumn\TextColumnSize::ExtraSmall)->numeric(2)->default($payroll->temp['personal_relief']);
                    $columns['housing_relief'] = TextColumn::make('housing_relief')->size(TextColumn\TextColumnSize::ExtraSmall)->numeric(2)->default($payroll->temp['housing_relief']);
                }
            }
        }


        return $table
            ->query(IPayroll::query())
            ->columns([
                TextColumn::make("employee_name")->size(TextColumn\TextColumnSize::ExtraSmall)->searchable(),
                ...$grossAndBasic,
                TextColumn::make("house allowance")->searchable()->size(TextColumn\TextColumnSize::ExtraSmall),
                TextColumn::make("transport allowance")->searchable()->size(TextColumn\TextColumnSize::ExtraSmall),
                TextColumn::make("medical allowance")->searchable()->size(TextColumn\TextColumnSize::ExtraSmall),
                TextColumn::make("fuel allowance")->searchable()->size(TextColumn\TextColumnSize::ExtraSmall),
                TextColumn::make('gross_pay')->numeric(2)->size(TextColumn\TextColumnSize::ExtraSmall),
                TextColumn::make('nhif')->label('N.H.I.F')->numeric(2)->size(TextColumn\TextColumnSize::ExtraSmall),
                ...$nssf,
                ... $benefitColumns,
                ...collect($columns)->reverse()->toArray(),
            ])
            ->filters([


            ])
            ->headerActions([
                FilamentExportBulkAction::make('Export'),
                Action::make('refresh payroll')
                    ->action(fn() => Artisan::call("app:temp-payroll"))
            ])
            ->actions([
                Action::make('view')->url(fn(Model $row) => EmployeeResource::getUrl(name: 'edit', parameters: ['record' => $row->employee_id]))
            ])
            ->bulkActions([
                // ...
            ])->striped();
    }

}
