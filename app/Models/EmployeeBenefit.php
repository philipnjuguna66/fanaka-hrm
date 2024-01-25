<?php

namespace App\Models;


use App\Services\PayrollService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;


class EmployeeBenefit extends Pivot
{
    use HasFactory;

    protected $table = "benefit_employee";

    protected $fillable = [

        'employee_id',
        'benefit_id',
        'amount'
    ];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class);
    }

    public function benefit(): BelongsTo
    {
        return $this->belongsTo(Benefit::class);
    }
}
