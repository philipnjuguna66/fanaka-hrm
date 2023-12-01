<?php

namespace App\Models;

use App\Enums\DeductionPercentageOf;
use App\Services\PayrollService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutoryDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ranges',
        'maximum'
    ];

    protected $casts = [
        'ranges' => 'array'
    ];

    public function deductionTypes()
    {
        return $this->belongsToMany(DeductionType::class);
    }

    public function getAmount(Employee $employee, $gross)
    {
        if (! $employee->should_pay_payee)
        {
            return  0;
        }

        $employee->loadMissing('salaryDetail');

        foreach ($this->ranges as $range) {
            $min = $range['min_range'];
            $max = $range['max_range'];
            $deduction = $range['deduction'];
            $type = $range['type'];

            if ($gross >= $min && $gross <= $max) {

                if ($type === "fixed_amount") {
                    return $deduction;
                }

                return  ($deduction / 100) *  $gross;

            }
        }

        return $this->maximum; // Return a default value if no range matches
    }

}
