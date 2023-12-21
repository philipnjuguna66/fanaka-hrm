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

        }


        TempPayroll::query()->delete();



        foreach ($data as $datum)
        {
            TempPayroll::create([
                'employee_name' => $datum['employee_name'],
                'employee_id' => $datum['employee_id'],
                'temp' => $datum,
            ]);
        }


    }
}
