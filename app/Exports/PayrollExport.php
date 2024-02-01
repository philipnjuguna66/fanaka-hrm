<?php

namespace App\Exports;

use App\Enums\PayrollReport;
use App\Models\Payroll;
use App\Models\PayrollLine;
use App\Services\PayrollService;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollExport implements FromCollection,WithHeadings
{

    public function __construct(public Model|Payroll $payroll,public PayrollReport $payrollExport)
    {
    }


    protected function nhifHeadings(): array
    {
        return [
            'PAYROLL NO',
            'LAST NAME',
            'FIRST NAME',
            'ID NO',
            'NHIF NO',
            'AMOUNT',
        ];
    }

    protected function payrollHeadings(): array
    {
        return [
            'PAYROLL NO',
            'LAST NAME',
            'FIRST NAME',
            'ID NO',
            'NHIF NO',
            'AMOUNT',
        ];
    }

    protected function nhifCollections()
    {
        return $this->payroll->payrollLines->map(fn(PayrollLine $line) => [
            'payroll_no' => $line->payroll->payroll_number,
            'last_name' => $line->employee->last_name,
            'first_name' => $line->employee->first_name,
            'id_no' => $line->employee->legal_document_number,
            'nhif_no' => $line->employee->nhif_no,
            'amount' => $line->statutory['nhif'] ?? 0,

        ]);
    }

    protected function houseLevyCollections()
    {
        return $this->payroll->payrollLines->map(fn(PayrollLine $line) => [
            'payroll_no' => $line->payroll->payroll_number,
            'last_name' => $line->employee->last_name,
            'first_name' => $line->employee->first_name,
            'id_no' => $line->employee->legal_document_number,
            'gross_pay' => $line->gross_pay,
            'amount' => $line->statutory['house levy'] ?? 0,

        ]);
    }

    protected function nssfCollections()
    {

        return $this->payroll->payrollLines->map(fn(PayrollLine $line) => [
            'payroll_no' => $line->payroll->created_at->format('Y-M'),
            'surname' => $line->employee->last_name,
            'other_names' => $line->employee->first_name .' '. $line->employee->middle_name,
            'id_no' => $line->employee->legal_document_number,
            'kra_pin' => $line->employee->kra_pin_no,
            'nssf_no' => $line->employee->nssf_no,
            'amount' => $line->statutory['nssf'] ?? 0,

        ]);
    }


    protected function payeeCollections()
    {

        return $this->payroll->payrollLines->map(fn(PayrollLine $line) => [
            'payroll_no' => $line->payroll->created_at->format('Y-M'),
            'surname' => $line->employee->last_name,
            'other_names' => $line->employee->first_name .' '. $line->employee->middle_name,
            'id_no' => $line->employee->legal_document_number,
            'kra_pin' => $line->employee->kra_pin_no,
            'payee' => $line->net_payee,
            'amount' => $line->statutory['nssf'] ?? 0,

        ]);
    }

    protected function nssfHeadings(): array
    {
        return [
            'PAYROLL NO',
            'SURNAME',
            'OTHER NAMES',
            'ID NO',
            'KRA PIN',
            'NSSF NO',
            'Amount',
        ];
    }
    protected function payeHeadings(): array
    {
        return [
            'SURNAME',
            'OTHER NAMES',
            'ID NO',
            'KRA PIN',
            'GROSS PAY',
            'PAYE',
        ];
    }

    protected function houseLevyHeadings(): array
    {
        return [
            'SURNAME',
            'OTHER NAMES',
            'ID NO',
            'KRA PIN',
            'GROSS PAY',
            'Amount',
        ];
    }


    public function headings(): array
    {
        return match ($this->payrollExport){
            PayrollReport::NSSF => $this->nssfHeadings(),
            PayrollReport::PAYE => $this->payeHeadings(),
            PayrollReport::NHIF => $this->nhifHeadings(),
            PayrollReport::HOUSE_LEVY => $this->houseLevyHeadings(),
            PayrollReport::PAYROLL => $this->payrollHeadings(),
        };
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Lifelong',
            'lastModifiedBy' => 'Lifelong',
            'title'          => 'Nhif Kra Export',
            'description'    => 'Nhif Kra Export',
            'subject'        => 'Nhif',
            'keywords'       => 'Nhif,export,Kra',
            'category'       => 'Tax',
            'manager'        => 'Fanaka Real Estate',
            'company'        => 'Fanaka Real Estate',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): \Illuminate\Support\Collection
    {
        return match ($this->payrollExport){
            default  => $this->nhifCollections(),
            PayrollReport::NSSF => $this->nssfCollections(),
            PayrollReport::PAYE => $this->nssfCollections(),
            PayrollReport::HOUSE_LEVY => $this->houseLevyCollections(),
        };
    }
}
