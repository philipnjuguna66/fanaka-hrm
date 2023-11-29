<?php

namespace App\Models;

use App\Enums\DeductionPercentageOf;
use App\Services\PayrollService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'deduction_type_id',
        'name',
        'type',
        'percentage_of',
        'percentage_value',
        'fixed_amount',
    ];

    protected $casts = [
        'percentage_of' => DeductionPercentageOf::class
    ];

    public function deductionType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeductionType::class);
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Employee::class);
    }
    public function default(Employee $employee)
    {
        $employee->loadMissing('salaryDetail');

        if ($this->type === "fixed_amount")  return $this->fixed_amount;


        if($this->percentage_of === DeductionPercentageOf::BasicSalary){

            return  ($this->percentage_value / 100) * $employee->salaryDetail->basic_salary;
        }

        if($this->percentage_of === DeductionPercentageOf::NHIF){

            return  ($this->percentage_value / 100) * app(PayrollService::class)->runPayrollForEmployee($employee)['nhif'];
        }



        return  ($this->percentage_value / 100) * app(PayrollService::class)->getGrossSalary($employee);
    }
}
