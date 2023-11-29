<?php

namespace App\Enums;

enum DeductionPercentageOf: string
{
    use toKeyValueOptions;
    case BasicSalary = 'basic_salary';
    case GrossSalary = 'gross_salary';
    case NHIF = 'nhif';
}
