<?php

namespace App\Jobs\Payslip;

use App\Actions\DownloadPayslip;
use App\Models\Payroll;
use App\Models\PayrollLine;
use App\Models\PaySlip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmailPayslip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(readonly  public  Payroll $payroll)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        /** @var PayrollLine $payrollLine */
        foreach ($this->payroll->payrollLines as $payrollLine) {
            (new DownloadPayslip())
            ->handle($payrollLine->payslip);

            (new DownloadPayslip())
                ->mail(
                    path: public_path('templates/results/'.$payrollLine?->employee?->name.'-payslip.docx'),
                    to: $payrollLine?->employee?->hrContact?->official_email,
                    subject: "Payslip for the Month of ". $payrollLine->created_at->format('Y-M'),
                );
        }
    }
}
