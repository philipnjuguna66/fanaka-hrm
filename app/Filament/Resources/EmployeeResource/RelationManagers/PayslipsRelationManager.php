<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Actions\DownloadPayslip;
use App\Models\Employee;
use App\Models\PaySlip;
use App\Services\Output\PdfOutPut;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayslipsRelationManager extends RelationManager
{
    protected static string $relationship = 'payslips';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('created_at')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->wrapHeader()
                    ->label('Month')
                    ->formatStateUsing(fn(Carbon $state) => str($state->year)->padRight(10)->append($state->monthName))
                    ->sortable(),
                Tables\Columns\TextColumn::make('payrollLine.payroll.payroll_number')
                    ->label('Payroll Number')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Download')
                    ->action(function (PaySlip $paySlip){

                         return (new DownloadPayslip())
                            ->download($paySlip);

                    })
                    ->color('primary')->icon('heroicon-o-arrow-down-tray'),
                Tables\Actions\Action::make('Email Payslip')
                    ->action(function (PaySlip $paySlip){

                        (new DownloadPayslip())
                            ->handle($paySlip);

                        PdfOutPut::make(
                            filePath:   public_path('templates/results/'.str($paySlip->payrollLine?->employee->name)->slug().'-payslip.docx'),
                            fileName: $paySlip->payrollLine?->employee?->name
                        )->output();

                        (new DownloadPayslip())
                            ->mail(
                                path: public_path('templates/results/'.str($paySlip->payrollLine?->employee->name)->slug().'-payslip.pdf'),
                                to: $paySlip->payrollLine?->employee?->hrContact?->official_email,
                                subject: "Payslip for the Month of ". $paySlip->payrollLine->created_at->format('Y-M'),
                            );

                    })
                    ->color('primary')->icon('heroicon-o-envelope'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                   // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
