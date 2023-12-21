<?php

namespace App\Models;

use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class IPayroll extends Model
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
        $data = [];

        $payroll_data =(new PayrollService)->runPayrollForAllEmployee();

        foreach ($payroll_data as $payroll_datum){

            $payrollData = $payroll_datum;

            unset($payroll_datum['statutory']);
            unset($payroll_datum['benefits']);
            unset($payroll_datum['deductions']);

            $data[] = $payroll_datum;

            /*foreach ($payrollData['benefits'] as $index => $benefit)
            {
                $data[$index] =  $benefit;

            }

            foreach ($payrollData['deductions'] as $index => $deduction)
            {
                $data[$index] =  $deduction;

            }*/

        }


        return $data;
    }

}
