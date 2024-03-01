<?php

namespace App\Actions;

use App\Models\PaySlip;
use App\Services\Output\PdfOutPut;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class DownloadPayslip
{

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function handle(PaySlip $paySlip)
    {
        $paySlip = $paySlip->loadMissing('payrollLine.employee',
            'payrollLine.employee.employeeBenefits.pivot',
            'payrollLine.employee.employeeDeductions.pivot',
            'payrollLine.payroll');

        $payslipTemplate = new TemplateProcessor(public_path('templates/payslip_template.docx'));

        $line= $paySlip->payrollLine;

        $payslipTemplate->setValue('employee_name', $line->employee?->name);
        $payslipTemplate->setValue('staff_no', $line->employee?->hrDetail?->staff_number);
        $payslipTemplate->setValue('job_title', $line->employee?->hrDetail?->jobTitle?->label);
        $payslipTemplate->setValue('payroll_period', Carbon::parse($paySlip->payrollLine?->payroll?->created_at)->format('Y-F'));
        $payslipTemplate->setValue('basic_pay', number_format($paySlip->payrollLine->basic_pay, 2));
        $payslipTemplate->setValue('gross_pay', number_format($paySlip->payrollLine->gross_pay));
        $payslipTemplate->setValue('nssf', number_format($paySlip->payrollLine->nssf, 2));
        $payslipTemplate->setValue('nhif', number_format($paySlip->payrollLine->nhif, 2));
        $payslipTemplate->setValue('net_paye', number_format( $paySlip->payrollLine->net_payee, 2));
        $payslipTemplate->setValue('net_pay',  number_format($paySlip->payrollLine->net_pay, 2));

        $payslipTemplate->cloneRow('benefits', $line->employee->employeeBenefits->count());
        $payslipTemplate->cloneRow('deductions', $line->employee->employeeDeductions->count());


        foreach ($line->employee->employeeBenefits as $index => $employeeBenefit) {


            $no =  $index + 1;
            $payslipTemplate->setValue("benefits#{$no}", $employeeBenefit->name);
            $payslipTemplate->setValue("benefit_amount#{$no}", number_format($employeeBenefit->pivot->amount, 2));
        }
        foreach ($line->statutory as $index => $statutory) {


           // $payslipTemplate->setValue("house_levy", 0);

        }

        foreach ($line->employee->employeeDeductions as $index => $employeeDeduction) {

            $no =  $index + 1;
            $payslipTemplate->setValue("deductions#{$no}", $employeeDeduction->name);
            $payslipTemplate->setValue("deduction_amount#{$no}", number_format($employeeDeduction->pivot->amount, 2));
        }


        $payslipTemplate->saveAs(public_path('templates/results/'.str($line?->employee->name)->slug() .'.docx'));

    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileExceptionls
     */
    public function download(PaySlip $paySlip): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $paySlip = $paySlip->loadMissing('payrollLine.employee','payrollLine.payroll');

        $this->handle($paySlip);

        $line= $paySlip->payrollLine;


        PdfOutPut::make(
            filePath:   public_path('templates/results/'. str($line->employee?->name)->slug('-') .'.docx'),
            fileName: $line->employee?->name
        )->output();


        return response()
            ->download(
                public_path("templates/results/".  str($line->employee?->name)->slug('-') .".pdf")
            )
            ->deleteFileAfterSend();

    }

    public function mail(string $path, string $to,string $fileName, string $subject): void
    {

        PdfOutPut::make(
            filePath:   $path,
            fileName: $fileName
        )->output();


        $path = str($path)->replace(".docx",".pdf")->toString();

        Mail::raw("Payslip", fn($message) => $message->to($to)
            ->subject($subject)
            ->attach($path, ['as' => "payslip.pdf"])
        );


    }



}
