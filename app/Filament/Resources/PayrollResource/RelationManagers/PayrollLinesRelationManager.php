<?php

namespace App\Filament\Resources\PayrollResource\RelationManagers;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
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

        $deductions = [];
        $statutory = [];
        $benefits = [];


        $data = [];

        foreach ($temps as $payroll) {


            foreach (Deduction::query()->whereNotIn('name', array_keys($payroll->deductions))->get() as $deduction) {

                $data[$payroll->employee_id] = TextColumn::make($deduction->name)->default(number_format(0, 2))->numeric(2);

            }

            foreach (Benefit::query()->whereNotIn('name', array_keys($payroll->benefits))->get() as $benefit) {

                $data[$payroll->employee_id] = TextColumn::make($benefit->name)->default(number_format(0, 2))->numeric(2);

            }

            foreach (StatutoryDeduction::query()->whereNotIn('name', array_keys($payroll->statutory))->get() as $statutory) {

                $data[$payroll->employee_id] = TextColumn::make($statutory->name)->default(number_format(0, 2))->numeric(2);

            }


            $grossAndBasic = [
                TextColumn::make('basic_pay')->numeric(2),
                TextColumn::make('gross_pay')->numeric(2),
            ];

            foreach ($payroll->benefits as $index => $value) {

                $benefits[$payroll->employee_id] = TextColumn::make($index)->numeric(2);

            }
            foreach ($payroll->statutory as $index => $value) {

                $statutory[$payroll->employee_id] = TextColumn::make($index)->numeric(2);

            }
            foreach ($payroll->deductions as $index => $value) {

                $deductions[$payroll->employee_id] = TextColumn::make($index)->numeric(2);

            }


        }

        return $table
            ->query(PayrollLine::query()->where('payroll_id', $this->getOwnerRecord()->getKey()))
            ->recordTitleAttribute('employee.first_name')
            ->columns([
                TextColumn::make("employee.name")->searchable(),
                ...$grossAndBasic,
                ...$benefits,
                ...$data,
                TextColumn::make('insurance_relief')->numeric(2),
                TextColumn::make('insurance_relief')->numeric(2),
                TextColumn::make('tax_allowable_deductions')->numeric(2),
                TextColumn::make('taxable_income')->numeric(2),
                ...$statutory,
                ...$deductions,
                TextColumn::make('net_payee')->numeric(2),
                TextColumn::make('net_pay')->numeric(2),
            ])
            ->filters([

            ])
            ->headerActions([
                FilamentExportBulkAction::make('Export')
            ])
            ->bulkActions([
                // ...
            ])->striped();
    }
}
