<?php

namespace App\Console\Commands;

use App\Models\TempPayroll;
use App\Services\PayrollService;
use Illuminate\Console\Command;

class CreateTempPayroll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:temp-payroll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a temperary payroll';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $temp = (new PayrollService)->runPayrollForAllEmployee();

$data = [];

        foreach ($temp as $payroll_datum){

            $payrollData = $payroll_datum;

            unset($payroll_datum['statutory']);
            unset($payroll_datum['benefits']);
            unset($payroll_datum['deductions']);

            $data[] = $payroll_datum;

            foreach ($payrollData['benefits'] as $index => $benefit)
            {
                $data[$index] =  $benefit;

            }

           /* foreach ($payrollData['statutory'] as $index => $statutory)
            {
                $data[$index] =  $statutory;

            }*/

            foreach ($payrollData['deductions'] as $index => $deduction)
            {
                $data[$index] =  $deduction;

            }

        }


        TempPayroll::query()->delete();

       // dd($data);

        foreach ($data as $datum)
        {


            TempPayroll::create([
                'employee_id' => $datum['employee_id'],
                'temp' => $datum,
            ]);
        }


    }
}
