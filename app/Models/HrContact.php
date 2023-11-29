<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HrContact extends Model
{
    use HasFactory;

    protected $casts = [
        'next_of_kin' => 'array',
        'social_links' => 'array',
    ];

    protected $fillable = [
        'employee_id',
        'official_email',
        'personal_email',
        'personal_phone_number',
        'office_phone_number',
        'office_phone_extension',
        'country',
        'city',
        'county',
        'postal_code',
        'next_of_kin',
        'social_links',
        'address'
    ];

}
