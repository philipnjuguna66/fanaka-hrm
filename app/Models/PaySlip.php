<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_line_id',
        'created_at'
    ];

    public function payrollLine()
    {
        return $this->belongsTo(PayrollLine::class);
    }

}
