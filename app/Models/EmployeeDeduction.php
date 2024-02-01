<?php

namespace App\Models;


use App\Services\PayrollService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;


class EmployeeDeduction extends Pivot
{
    use HasFactory;

    protected $table = "deduction_employees";
    public $incrementing = true;

    protected $fillable = [
        'employee_id',
        'deduction_id',
        'amount',
    ];

    public function employee(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class);
    }

    public function deduction(): BelongsTo
    {
        return $this->belongsTo(Deduction::class);
    }
}
