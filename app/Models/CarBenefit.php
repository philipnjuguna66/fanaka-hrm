<?php

namespace App\Models;

use App\Filament\Resources\EmployeeResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CarBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'car_reg_no',
        'make',
        'body_type',
        'cc_rating',
        'type_of_car_cost',
        'cost_of_owned_car',
        'cost_of_hiring',
        'commissioner_rate',
        'benefit_rate',
    ];


    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function total()
    {
        $this->loadMissing(['employee','benefit']);

        if ($this->type === "fixed_amount") {

            $val = $this->fixed_amount;

        } else {

            $val = ((int)$this->percentage_value / 100) * $this->employee->salaryDetail->basic_salary;
        }

        if($this->benefit->capped){

            $val =  min($val,$this->benefit->cap_limit);

        }
        return $val;

    }

}
