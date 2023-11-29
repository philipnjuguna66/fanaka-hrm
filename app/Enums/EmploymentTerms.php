<?php

namespace App\Enums;

enum EmploymentTerms:string
{
    use toKeyValueOptions;
  case CONTRACT = 'contract';
  case PERMANENT = 'permanent';
}
