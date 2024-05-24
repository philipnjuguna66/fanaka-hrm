<?php

namespace App\Models;

use App\Casts\PayrollNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_number',
        'created_at',
    ];



    public function payrollLines(): HasMany
    {
        return $this->hasMany(PayrollLine::class);
    }


    public function payrollNumber(): Attribute
    {
        return Attribute::make(
            get: fn($value) : string => 'PAYR-'. $value,
        );
    }
}
