<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PayrollSettings extends Settings
{

    public static function group(): string
    {
        return 'payroll';
    }
}