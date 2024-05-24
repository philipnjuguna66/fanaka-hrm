<?php

namespace App\Filament\Resources\PayrollResource\RelationManagers;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\EmployeeResource;
use App\Models\Benefit;
use App\Models\Deduction;
use App\Models\FinalPayroll;
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
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Artisan;

class PayrollLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'payrollLines';

    protected $footer = [];

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

        $footer = [];


        $relief = [];

        foreach ($temps as  $payroll) {


            foreach ($payroll->deductions as $index => $value) {
                $footer[$index] = $index;

                $columns[$index] = TextColumn::make($index)->searchable()->default(number_format(floatval($value), 2))->numeric(2);

            }
            foreach ($payroll->benefits as $index => $value) {

                $columns[$index] = TextColumn::make($index)->searchable()->default(number_format(floatval($value), 2))->numeric(2);

            }
            foreach ($payroll->statutory as $index => $value) {

                $columns[$index] = TextColumn::make($index)->searchable()->default(number_format(floatval($value), 2))->numeric(2);


                if ([str($index)->lower()->slug('_')->value()] == 'house_levy')
                {

                    $relief['housing_relief'] = TextColumn::make("housing_relief")->searchable()->default(number_format(floatval($value)* 0.15, 2))->numeric(2);

                }
            }
        }

        $this->footer = $footer;


        return $table
            ->query(FinalPayroll::query()->where('payroll_id', $this->getOwnerRecord()->getKey()))
            ->columns([
                TextColumn::make("employee_name")->searchable(),
                TextColumn::make('basic_pay')->numeric(2)->searchable(),
                TextColumn::make('gross_pay')->numeric(2),
                TextColumn::make('tax_allowable_deductions')->numeric(2),
                TextColumn::make('taxable_income')->numeric(2),
                ...$grossAndBasic,
                ...collect($columns)->reverse()->toArray(),
                TextColumn::make('personal_relief')->numeric(2),
                TextColumn::make('insurance_relief')->numeric(2),
                TextColumn::make('housing_relief')->numeric(2),
                 ...$relief,
                TextColumn::make('net_payee')->numeric(2),
                TextColumn::make('net_pay')->numeric(2),
            ])
            ->filters([

            ])
            ->headerActions([
                FilamentExportBulkAction::make('Export'),
            ])
            ->actions([
                Action::make('view')->url(fn(Model $row) => EmployeeResource::getUrl(name: 'edit', parameters: ['record' => $row->employee_id]))
            ])
            ->bulkActions([
                // ...
            ])->striped();
    }

    protected function getTableContentFooter(): ?View
    {
        return view('table.footer', [
            'calc_columns' => [
                'basic_pay',
                'gross_pay',
                'tax_allowable_deductions',
                'taxable_income',
                'personal_relief',
                'insurance_relief',
                'net_payee',
                'net_pay',
                ...$this->footer
            ]
        ]);
    }
}
