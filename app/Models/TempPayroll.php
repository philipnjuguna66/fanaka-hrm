<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPayroll extends Model
{
    use HasFactory;

    protected $casts = [
        'temp' => 'json'
    ];
    protected $guarded = [];

}
