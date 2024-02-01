<?php

namespace App\Enums;

enum PayrollReport:string
{
   case  NHIF = 'NHIF';
   case  NSSF = 'NSSF';
   case  PAYE = 'PAYE';
   case  HOUSE_LEVY = 'HOUSE_LEVY';
   case  PAYROLL = 'PAYROLL';
}
