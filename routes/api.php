<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::any('/commissions', function (Request $request){

    $employee = \App\Models\HrDetail::query()->where('staff_number', $request->employee_id)->first();


    if ($employee)
    {
        $benefit= \App\Models\Benefit::updateOrCreate([
            'code' => "commission"
        ],
            [
                'name' => "Commissions",
                'taxable' => 1,
                'non_cash' => 0,
                'mode' => "monthly",
                'taxed_from' => 0,
                'type' => "fixed_amount",
                'fixed_amount' => "0",
                'percentage_of' => null,
                'percentage_value' => null,
            ]
        );


        if (filled($benefit->id))
        {
            \App\Models\EmployeeBenefit::updateOrCreate([
                'benefit_id' => $benefit->id,
                'employee_id' => $employee->employee_id,
            ],[
                'amount' => $request->amount
            ]);
        }
        else
        {

        }
    }

});
