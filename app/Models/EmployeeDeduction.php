<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;


class EmployeeDeduction extends Pivot
{
    use HasFactory;

    protected $table = "deduction_employee";
    public $incrementing = true;

    protected $fillable = [
        'employee_id',
        'deduction_id',
        'amount',
    ];

    public function employee(): belongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function deduction(): BelongsTo
    {
        return $this->belongsTo(Deduction::class);
    }
}
