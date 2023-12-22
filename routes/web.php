<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/admin');


Route::get('/test', function (){


      /** @var \App\Models\Employee $employee */

    foreach (\App\Models\Employee::all() as $employee) {

        if ($employee->should_pay_payee)
        {
            $employee->employeeDeductions()->attach([4 => ['amount' => 300 ]]);
        }

    }


});
