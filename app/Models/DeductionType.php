<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeductionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'mode',
        'tax_allowable',
        'tax_relief',
        'capped',
        'cap_limit',
    ];

    public function deductions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Deduction::class);
    }

    public function statutoryDeductions()
    {

        return $this->belongsToMany(StatutoryDeduction::class);

    }
}
