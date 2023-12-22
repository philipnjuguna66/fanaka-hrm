<?php

namespace App\Filament\Resources\PayrollResource\Pages;

use App\Enums\PayrollReport;
use App\Exports\PayrollExport;
use App\Filament\Resources\PayrollResource;
use App\Jobs\Payslip\EmailPayslip;
use App\Models\Payroll;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Maatwebsite\Excel\Facades\Excel;

class ViewPayroll extends ViewRecord
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\EditAction::make(),

            Actions\Action::make('email-payslip')
            ->action(function (){
                /** @var Payroll $payroll */
                $payroll = $this->record;
                dispatch(new EmailPayslip(payroll: $payroll));

            }),
            Actions\Action::make('NHIF')->action(function (){
                return Excel::download(new PayrollExport($this->record,PayrollReport::NHIF), 'nhif.xlsx');
            })->label('N.H.I.F')->color('primary')->icon('heroicon-o-arrow-down-tray'),

            Actions\Action::make('NSSF')->action(function (){
                return Excel::download(new PayrollExport($this->record,PayrollReport::NSSF), 'nssf.xlsx');
            })->label('N.S.S.F')->color('primary')->icon('heroicon-o-arrow-down-tray'),

            Actions\Action::make('P.A.Y.E')->action(function (){
                return Excel::download(new PayrollExport($this->record,PayrollReport::PAYE), 'paye.xlsx');
            })->label('P.A.Y.E')->color('primary')->icon('heroicon-o-arrow-down-tray'),

            Actions\Action::make('HOUSE LEVY')->action(function (){
                return Excel::download(new PayrollExport($this->record,PayrollReport::HOUSE_LEVY), 'house_levy.xlsx');
            })->label('House Levy')->color('primary')->icon('heroicon-o-arrow-down-tray'),
        ];
    }
}
