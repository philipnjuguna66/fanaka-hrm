<?php

namespace App\Filament\Resources\PayrollResource\Pages;

use App\Enums\PayrollReport;
use App\Exports\PayrollExport;
use App\Filament\Resources\PayrollResource;
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

            Actions\Action::make('NHIF')->action(function (){
                return Excel::download(new PayrollExport($this->record,PayrollReport::NHIF), 'nhif.xlsx');
            })->label('N.H.I.F')->color('primary')->icon('heroicon-o-arrow-down-tray'),
            Actions\Action::make('NSSF')->action(function (){
                return Excel::download(new PayrollExport($this->record,PayrollReport::NSSF), 'nssf.xlsx');
            })->label('N.S.S.F')->color('primary')->icon('heroicon-o-arrow-down-tray'),
            Actions\Action::make('NSSF')->action(function (){
                return Excel::download(new PayrollExport($this->record,PayrollReport::PAYE), 'paye.xlsx');
            })->label('PAYE')->color('primary')->icon('heroicon-o-arrow-down-tray'),
        ];
    }
}
