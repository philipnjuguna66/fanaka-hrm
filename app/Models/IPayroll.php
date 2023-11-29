<?php

namespace App\Models;

use App\Services\PayrollService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class IPayroll extends Model
{
    use Sushi;

    public function getRows()
    {
        return (new PayrollService)->runPayrollForAllEmployee();
    }

}
