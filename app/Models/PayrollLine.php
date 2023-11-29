<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PayrollLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_id',
        'basic_pay',
        'gross_pay',
        'tax_allowable_deductions',
        'car_benefits',
        'housing_benefits',
        'taxable_income',
        'nhif',
        'nssf',
        'paye',
        'personal_relief',
        'insurance_relief',
        'net_payee',
        'net_pay',
        'deductions',
        'statutory',
        'benefits',
    ];


    protected $casts = [
        'benefits' => 'json',
        'deductions' => 'json',
        'statutory' => 'json',
    ];


    public function payslip(): HasOne
    {
        return $this->hasOne(PaySlip::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }
}
