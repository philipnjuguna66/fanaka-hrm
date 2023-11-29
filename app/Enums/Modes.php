<?php

namespace App\Enums;

enum Modes:string
{

    use toKeyValueOptions;

    case MONTHLY = 'monthly';
    case DAILY = 'daily';
    case HOURLY = 'hourly';


}
