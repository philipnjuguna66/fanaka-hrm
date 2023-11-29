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

    protected $fillable = [
        'employee_id',
        'payroll_id',
        'basic_pay',
        'gross_pay',
        'tax_allowable_deductions',
        'car_benefits',
        'housing_benefits',
        'taxable_income',
        'nhif',
        'nssf',
        'paye',
        'personal_relief',
        'insurance_relief',
        'net_payee',
        'net_pay',
        'deductions',
        'statutory',
        'benefits',
    ];

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

            $data[] = [
                'employee_id' => $payroll_datum['employee_id'],
                'basic_pay' => $payroll_datum['basic_pay'],
                'gross_pay' => $payroll_datum['gross_pay'],
                'tax_allowable_deductions' => $payroll_datum['tax_allowable_deductions'],
                'car_benefits' => $payroll_datum['car_benefits'],
                'housing_benefits' => $payroll_datum['housing_benefits'],
                'taxable_income' => $payroll_datum['taxable_income'],
                'nhif' => $payroll_datum['nhif'],
                'nssf' => $payroll_datum['nssf'],
                'house_levy' => $payroll_datum['house_levy'] ?? 0,
                'personal_relief' => $payroll_datum['personal_relief'],
                'insurance_relief' => $payroll_datum['insurance_relief'],
                'net_payee' => $payroll_datum['net_payee'],
                'paye' => $payroll_datum['paye'],
                'net_pay' => $payroll_datum['net_pay'],
            ];
        }


        return $data;
    }

}
