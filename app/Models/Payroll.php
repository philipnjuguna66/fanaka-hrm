<?php

namespace App\Models;

use App\Casts\PayrollNumber;
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

    protected $casts = [
        'payroll_number' => PayrollNumber::class
    ];




    public function payrollLines(): HasMany
    {
        return $this->hasMany(PayrollLine::class);
    }


    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            $model->payroll_number = DB::table('payrolls')->max('payroll_number') + 1;
        });
    }
}
