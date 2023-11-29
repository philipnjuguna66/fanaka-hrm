<?php

namespace App\Filament\Pages;

use App\Livewire\PayrollPreview;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollService;
use Carbon\Carbon;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class PayrollMaster extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.pages.payroll-master';
    protected static ?string $navigationGroup = 'Payroll';


    protected function getHeaderActions(): array
    {

        return  [

            Action::make('Run PayRoll')

                ->form([
                    DatePicker::make('start_month')
                        ->maxDate(today()->startOfMonth()->toDateString())
                        ->label('Payroll Month')
                        ->native(false)
//                        ->rules([
//                        function () {
//                            return function (string $attribute, $value, Closure $fail) {
//                                $date = Carbon::parse($value)->startOfMonth()->startOfDay();
//                                if(Payroll::whereDate('created_at',$date->toDateString())->exists()){
//                                    $fail('The payroll has already been run.');
//                                }
//
//                                if(!Employee::count()){
//                                    $fail('There are no employees ');
//                                }
//                            };
//                        },
//                    ])
                    ->required()
                ])
                ->icon('heroicon-o-arrow-path')
                ->action(function (array $data){

               $payroll_data =  (new PayrollService())->runPayrollForAllEmployee();



               try{
                   DB::beginTransaction();
                   $date = Carbon::parse($data['start_month'])->startOfMonth()->startOfDay()->toDateTimeString();

                   $payroll =  Payroll::firstOrCreate([
                       'created_at' => $date,
                   ]);

                   foreach ($payroll_data as $payroll_datum){
                       $payroll_datum['created_at'] = $date;
                       unset($payroll_datum['employee_name']);


                   }
                   $payroll->payrollLines()->createUpdateOrDelete($payroll_data);

                   DB::commit();

                   Notification::make()->title('Payroll Run Complete')->success()->send();

               }catch (\Exception $exception){

                   Notification::make()->title('Payroll Run Failed')->body($exception->getMessage())->danger()->send();

                   DB::rollBack();

                   throw $exception;

               }

            })
        ];
    }


}
