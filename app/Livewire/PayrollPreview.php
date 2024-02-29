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

        $benefits = [];

        foreach ($temps as $payroll) {

            $basicPay = floatval($payroll->temp['basic_pay']);
            $grossPay = floatval($payroll->temp);

            $grossAndBasic = [
                TextColumn::make('basic_pay')->numeric(2)->default(number_format($grossPay, 2))->searchable(),

            ];

            foreach ($payroll->temp as $index => $value) {



                if (!in_array($index, ["net_pay", 'paye', 'employee_id', "gross_pay", "net_payee", 'car_benefits', 'housing_benefits', 'personal_relief', 'insurance_relief'])) {

                    $columns[] = TextColumn::make($index)->searchable()->default(number_format(floatval($value), 2))->numeric(2);
                } else {
                    $columns['net_pay'] = TextColumn::make('net_pay')
                        ->default(number_format($payroll->temp['net_pay'], 2))
                        ->numeric(2);
                    // $columns['paye'] = TextColumn::make('paye')->default(number_format($payroll->temp['paye'], 2 ))->numeric(2);;
                    $columns['net_payee'] = TextColumn::make('net_payee')
                        ->label('Payee')->default(number_format($payroll->temp['net_payee'], 2))->numeric(2);;
                    $columns['insurance_relief'] = TextColumn::make('insurance_relief')->numeric(2)->default(number_format($payroll->temp['insurance_relief'], 2));
                    $columns['personal_relief'] = TextColumn::make('personal_relief')->numeric(2)->default(number_format($payroll->temp['personal_relief'], 2));
                }

                foreach (Benefit::query()->whereNotIn('name', array_keys($payroll->temp))->get() as $benefit) {

                    $benefits[] =  TextColumn::make($benefit->name)->numeric(2)->default(number_format(floatval($value), 2))->searchable();

                }
            }


        }


        return $table
            ->query(IPayroll::query())
            ->columns([
                TextColumn::make("employee_name")->searchable(),
                ...$grossAndBasic,
                ... $benefits,
                TextColumn::make('gross_pay')->numeric(2)->default(number_format($basicPay, 2)),
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
