<?php

namespace App\Models;


use App\Enums\EmploymentTerms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'basic_salary',
        'disability_exemption_amount',
        'exemption_certificate_no',
        'processing_option',
        'employee_id',
        'has_disability',
        'disability_exemption_amount',
        'disability_exemption_certificate_number',
        'terms_of_employment'
        ];


    protected $casts = [
        'terms_of_employment' => EmploymentTerms::class
    ];

}
