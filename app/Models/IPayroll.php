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
       return TempPayroll::query()->get()->map(function (TempPayroll $payroll){
           return ['employee_id' => $payroll->employee_id, 'employee_name' => $payroll->employee_name, ...$payroll->temp];
       })
           ->toArray();
    }

}
