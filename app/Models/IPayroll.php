<?php

namespace App\Models;

use App\Services\PayrollService;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
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
        $payrolls = TempPayroll::query()
            ->get();

        $data = [];

        foreach ($payrolls as $index =>  $payroll) {

            $data[] = [
                'employee_name' => $payroll->employee_name,
                'employee_id' => $payroll->employee_id,
                ... $payroll->temp
            ];


            foreach (Benefit::query()->whereNotIn('name', array_keys($payroll->temp))->get() as $benefit) {

                $data[$index][str($benefit->name)->lower()->value()] = 0;

            }

            foreach (Deduction::query()->whereNotIn('name', array_keys($payroll->temp))->get() as $deduction) {

             $data[$index][str($deduction->name)->lower()->value()] = 0;

            }




        }

       return $data;
    }

}
