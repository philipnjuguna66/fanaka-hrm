<?php

namespace App\Models;

use App\Services\PayrollService;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class FinalPayroll extends Model
{
    use Sushi;

    protected $guarded = [];
    protected $casts = [
        'benefits' => 'json',
        'deductions' => 'json',
        'statutory' => 'json',
    ];



    public function getRows()
    {
        $payrolls = PayrollLine::query()
            ->get();

        $data = [];

        foreach ($payrolls as $index =>  $payroll) {

            $data[] = [
                'employee_name' => $payroll->employee->name,
                'employee_id' => $payroll->employee_id,
                'payroll_id' => $payroll->payroll_id,
                ... $payroll->deductions,
                ... $payroll->benefits,
                ... $payroll->statutory,
            ];



            foreach (Deduction::query()->whereNotIn('name', array_keys($payroll->deductions))->get() as $deduction) {

             $data[$index][str($deduction->name)->lower()->value()] = 0;

            }

            foreach (Benefit::query()->whereNotIn('name', array_keys($payroll->benefits))->get() as $benefit) {

                $data[$index][str($benefit->name)->lower()->value()] = 0;

            }

            foreach (StatutoryDeduction::query()->whereNotIn('name', array_keys($payroll->statutory))->get() as $statutory) {

                $data[$index][str($statutory->name)->lower()->value()] = 0;

            }



        }

       return $data;
    }

}
