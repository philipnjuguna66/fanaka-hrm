<?php

namespace App\Models;

use App\Enums\Modes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Benefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'taxable',
        'code',
        'non_cash',
        'mode',
        'taxed_from_amount',
        'type',
        'fixed_amount',
        'percentage_of',
        'percentage_value',
    ];

    protected $casts = [
        'mode' => Modes::class,
        'taxable'=>'boolean',
        'non_cash'=>'boolean'
    ];

    public function employees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Employee::class);
    }

    public function default(Employee $employee)
    {
        $employee->loadMissing('salaryDetail');

        if ($this->type === "fixed_amount")  return $this->fixed_amount;

        return  ($this->percentage_value / 100) * $employee->salaryDetail->basic_salary;
    }

}
