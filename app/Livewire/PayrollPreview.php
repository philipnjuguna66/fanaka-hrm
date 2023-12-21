<?php

namespace App\Livewire;

use App\Filament\Resources\EmployeeResource;
use App\Models\Benefit;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\EmployeeDeduction;
use App\Models\IPayroll;
use App\Models\StatutoryDeduction;
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
                $columns[] = TextColumn::make($index)->default($value);
            }
        }



        return $table
            ->query(TempPayroll::query())
            ->columns([
                ...$columns,
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
