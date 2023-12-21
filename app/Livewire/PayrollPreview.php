<?php

namespace App\Livewire;

use App\Filament\Resources\EmployeeResource;

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

        foreach ($temps as  $payroll) {

            foreach ($payroll->temp as $index => $value)
            {
                if (! in_array($index, ["net_pay",'paye','employee_id',"net_payee",'car_benefits','housing_benefits','personal_relief','insurance_relief']))
                {

                    $columns[] = TextColumn::make($index)->default($value);
                }
                else{
                    $columns['net_pay'] = TextColumn::make('net_pay')->default($payroll->temp['net_pay']);
                    $columns['paye'] = TextColumn::make('paye')->default($payroll->temp['paye']);
                    $columns['net_payee'] = TextColumn::make('net_payee')->default($payroll->temp['net_payee']);
                    $columns['insurance_relief'] = TextColumn::make('insurance_relief')->default($payroll->temp['insurance_relief']);
                    $columns['personal_relief'] = TextColumn::make('personal_relief')->default($payroll->temp['personal_relief']);
                }
            }
        }



        return $table
            ->query(TempPayroll::query())
            ->columns([
                TextColumn::make("employee_name"),
                ...collect($columns)->reverse()->toArray(),
            ])
            ->filters([


            ])
            ->headerActions([
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
