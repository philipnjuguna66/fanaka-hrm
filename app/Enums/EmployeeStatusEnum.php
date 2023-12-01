<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EmployeeStatusEnum: string implements HasLabel
{

    case ACTIVE="Active";
    case NOT_ACTIVE="Not_Active";


    public function getLabel() : string
    {
        return match ($this){
            static::ACTIVE => "Active",
            static::NOT_ACTIVE => "Not Active",

        };

    }

}
