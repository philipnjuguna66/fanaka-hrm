<?php

namespace App\Filament\Resources\PayrollResource\RelationManagers;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\EmployeeResource;
use App\Models\Benefit;
use App\Models\Deduction;
use App\Models\IPayroll;
use App\Models\PayrollLine;
use App\Models\StatutoryDeduction;
use App\Models\TempPayroll;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Artisan;

class PayrollLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'payrollLines';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {

        $temps = PayrollLine::query()->where('payroll_id', $this->getOwnerRecord()->getKey())->get();

        $columns = [];

        $grossAndBasic = [];

        foreach ($temps as $payroll) {

            $basicPay = floatval($payroll->temp['basic_pay']);
            $grossPay = floatval($payroll->temp);

            $grossAndBasic = [
                TextColumn::make('basic_pay')->numeric(2)->default(number_format($grossPay, 2))->searchable(),
                TextColumn::make('gross_pay')->numeric(2)->default(number_format($basicPay, 2)),
            ];

            foreach ($payroll->temp as $index => $value) {


                if (!in_array($index, ["net_pay", 'paye', 'employee_id', "gross_pay", "net_payee", 'car_benefits', 'housing_benefits', 'personal_relief', 'insurance_relief'])) {

                    $columns[] = TextColumn::make($index)->default(number_format(floatval($value), 2))->numeric(2);
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
            }
            return $table
                ->query(PayrollLine::query())
                ->recordTitleAttribute('employee.first_name')
                ->columns([
                    TextColumn::make("employee.name")->searchable(),
                    ...$grossAndBasic,
                    ...collect($columns)->reverse()->toArray(),
                ])
                ->filters([

                ])
                ->bulkActions([
                    // ...
                ])->striped();
        }
    }
}
