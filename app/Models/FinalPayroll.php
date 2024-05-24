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
            ->with('employee','payroll')
            ->get();

        $data = [];

        foreach ($payrolls as $index =>  $payroll) {

            $data[] = [
                'employee_name' => $payroll->employee->name,
                'basic_pay' => $payroll->basic_pay,
                'gross_pay' => $payroll->gross_pay,
                'tax_allowable_deductions' => $payroll->tax_allowable_deductions,
                'taxable_income' => $payroll->taxable_income,
                'personal_relief' => $payroll->personal_relief,
                'insurance_relief' => $payroll->insurance_relief,
                'net_payee' => $payroll->net_payee,
                'net_pay' => $payroll->net_pay,
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

            foreach ($payroll->statutory as $statutory => $value) {


                if (str($statutory)->lower()->slug('_')->value() == 'house_levy')
                {

                    if (Carbon::parse($payroll->payroll->created_at)->isSameMonth(Carbon::create(year: 2024,month: 04,day: 01)))
                    {
                        $data[$index]['housing_levy_relief'] =  floatval($value)* 0.15;
                    }
                    else{
                        $data[$index]['housing_levy_relief'] =  0;
                    }

                }
            }
        }

       return $data;
    }

}
