<?php

namespace App\Observers;

use App\Models\PayrollLine;

class PayrollLineObserver
{
    /**
     * Handle the PayrollLine "created" event.
     */
    public function created(PayrollLine $payrollLine): void
    {
        $payrollLine->payslip()->firstOrCreate([
            'created_at' => $payrollLine->payroll->created_at
        ]);
    }

    /**
     * Handle the PayrollLine "updated" event.
     */
    public function updated(PayrollLine $payrollLine): void
    {
        //
    }

    /**
     * Handle the PayrollLine "deleted" event.
     */
    public function deleted(PayrollLine $payrollLine): void
    {
        //
    }

    /**
     * Handle the PayrollLine "restored" event.
     */
    public function restored(PayrollLine $payrollLine): void
    {
        //
    }

    /**
     * Handle the PayrollLine "force deleted" event.
     */
    public function forceDeleted(PayrollLine $payrollLine): void
    {
        //
    }
}
