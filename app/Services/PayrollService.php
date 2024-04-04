<?php

namespace App\Services;

use App\Enums\EmployeeStatusEnum;
use App\Models\Benefit;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\StatutoryDeduction;
use Illuminate\Database\Eloquent\Collection;

class PayrollService
{
    public function runPayrollForAllEmployee(): array
    {
        $employees = Employee::query()->active()->get();

        return $employees->map(fn($employee) => $this->runPayrollForEmployee($employee))->all();
    }

    public function runPayrollForEmployee(Employee $employee): array
    {

        $gross = $this->getGrossSalary($employee);


        $statutory = StatutoryDeduction::all()->map(function ($deduction) use ($gross, $employee) {
            return [str($deduction->name)->lower()->value() => $deduction->getAmount(employee: $employee, gross: $gross)];
        })->collapse()->all();


        return [
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'basic_pay' => $employee->salaryDetail->basic_salary,
            'gross_pay' => $gross,
            'tax_allowable_deductions' => $this->calculateTaxAllowableDeductions($employee),
            'car_benefits' => $this->calculateCarBenefits($employee),
            'housing_benefits' => $this->calculateHousingBenefits($employee, $this->getGrossSalary($employee)),
            'taxable_income' => $this->getTaxableIncome($employee),
            ...$statutory,
            'paye' => $this->calculatePayee(employee: $employee, taxableIncome: $this->getTaxableIncome($employee)),
            'withholding_tax' => $this->getWithhodlingTax(employee: $employee, incomeTaX: $this->getTaxableIncome($employee)),
            'personal_relief' => $this->getPersonalRelief($employee),
            'insurance_relief' => $this->calculateInsuranceRelief($employee),
            'net_payee' => $this->getNetPayee($employee),
            'net_pay' => $this->getNetPay($employee),
            'deductions' => $this->getAllDeductionArrayKeys($employee),
            'benefits' => $this->getAllBenefitsArrayKeys($employee),
            ... $this->getAllBenefitsArrayKeys($employee),
            ...$this->getAllDeductionArrayKeys($employee),
            'statutory' => $statutory,
        ];
    }

    public function getWithhodlingTax(Employee $employee, $incomeTaX)
    {
        if ($employee->should_pay_payee) {
            return 0;
        }

        $gross =  $this->getGrossSalary($employee);

        if ($gross >= 24_001)
        {
            return (5 / 100) * $gross;
        }

        return  0;

    }

    protected function calculatePayee(Employee $employee, $taxableIncome): float|int
    {


        if (! $employee->should_pay_payee) {
            return 0;
        }

        $bands = [];

        // less than 24000
        if ($taxableIncome < 24000) {
            return 0;
        }

        if ($taxableIncome <= 32_333) {
            $bands[] = 24000 * 0.1;
            $bands[] = ($taxableIncome - 24000) * 0.25;
        }


        if ($taxableIncome >= 32_334) {
            $bands[] = 24000 * 0.1;
            $bands[] = (32_333 - 24000) * 0.25;
            $bands[] = ($taxableIncome - 32_333) * 0.30;
        }

        return round(array_sum($bands));

    }


    public function calculateCarBenefits(Employee $employee): float|int
    {

        $employee->refresh()->loadMissing('carBenefits');


        $costs = [];
        //hired/owned

        foreach ($employee->carBenefits as $carBenefit) {

            if ($carBenefit->type_of_car_cost === "hired") {

                $costs[] = $carBenefit->cost_of_hiring;

            } else {

                $percentage = $carBenefit->benefit_rate * $carBenefit->cost_of_owned_car;

                if ($percentage > $carBenefit->commissioner_rate) {

                    $costs[] = $percentage;
                } else {

                    $costs[] = $carBenefit->commissioner_rate;
                }
            }
        }

        return array_sum($costs);
    }

    protected function calculateHousingBenefits(Employee $employee, $grossPay): float|int
    {
        $employee->refresh()->loadMissing('housingBenefits');

        $costs = [];

        foreach ($employee->housingBenefits as $housingBenefit) {

            if ($housingBenefit->rate) {

                $picked = null;
                $percentage = $housingBenefit->rate * $grossPay;
                if ($percentage <= 0) $percentage = 0;
                if ($percentage > $housingBenefit->fair_market_rent_value) {
                    $picked = $percentage;
                } else {
                    $picked = $housingBenefit->fair_market_rent_value;
                }
                $result = $picked - $housingBenefit->rent_recovered;
                if ($result <= 0) $result = 0;
                $costs[] = $result;
            } else {
                $rentPicked = max($housingBenefit->actual_rent_value, $housingBenefit->fair_market_rent_value);
                $rentedResult = $rentPicked - $housingBenefit->rent_recovered;
                if ($rentedResult <= 0) $rentedResult = 0;
                $costs[] = $rentedResult;
            }
        }

        return array_sum($costs);

    }

    /**
     * @param Employee $employee
     * @return mixed
     */
    public function getGrossSalary(Employee $employee): mixed
    {

        $employee->refresh()->loadMissing(['employeeBenefits', 'salaryDetail']);

        return $employee->employeeBenefits
                ->sum('pivot.amount') + $employee->salaryDetail->basic_salary;
    }


    private function calculateTaxAllowableDeductions(Employee $employee)
    {

        if (! $employee->should_pay_payee) {
            return 0;
        }


        $gross = $this->getGrossSalary($employee);

        $employee->refresh()->loadMissing('employeeDeductions'); //

        $nssf = StatutoryDeduction::query()->where('name', 'NSSF')->first();

        return ($nssf->getAmount($employee, $gross));


    }

    private function getTaxableIncome($employee)
    {
        if (! $employee->should_pay_payee) {
            return 0;
        }

        $gross = $this->getGrossSalary($employee);

        return $gross - $this->calculateTaxAllowableDeductions($employee);
    }

    private function getPersonalRelief(?Employee $employee = null): int
    {
        if (! $employee->should_pay_payee) {
            return 0;
        }

        if ($this->calculatePayee($employee, $this->getTaxableIncome($employee)))
        {
            return 2400;
        }
        return 0;
    }

    protected function calculateInsuranceRelief($employee)
    {

        if (! $employee->should_pay_payee) {
            return 0;
        }
        $gross = $this->getGrossSalary($employee);

        if ($gross < 24000)
        {
            return  0;
        }


        $nhif =  StatutoryDeduction::query()->where('name', 'NHIF')->first();

        return ($nhif->getAmount($employee, $gross)) *0.15;

    }

    private function getNetPayee(Employee $employee)
    {
        if (! $employee->should_pay_payee) {
            return 0;

        }

        if (($this->calculatePayee($employee, $this->getTaxableIncome($employee)) - $this->getPersonalRelief($employee)) <= 0)
        {
            return  0;
        }

        return  ($this->calculatePayee($employee, $this->getTaxableIncome($employee))
            - $this->getPersonalRelief($employee)
            - $this->calculateInsuranceRelief($employee));



    }

    private function getNetPay(Employee $employee)
    {

        return $this->getGrossSalary($employee)
           - $this->getWithhodlingTax($employee, $this->getTaxableIncome($employee))
            - $this->getNetPayee($employee)
            - $this->totalStatutoryDeductions($employee)
            - $this->getAllDeductions($employee);
    }


    private function getAllDeductions($employee)
    {

        $employee->refresh()->loadMissing('employeeDeductions');

        return $employee->employeeDeductions
            ->groupBy('deduction_type_id')
            ->sum(function (Collection $deductions) {
                return $deductions->sum('pivot.amount');
            });
    }

    private function getAllBenefitsArrayKeys(Employee $employee)
    {

        $employee->refresh()->loadMissing('employeeBenefits');

        return $employee->employeeBenefits?->map(function (Benefit $benefit) {
            return [
                str($benefit->name)->lower()->value() => $benefit->pivot->amount,
            ];
        })
            ->collapse()->all();

    }

    private function getAllDeductionArrayKeys(Employee $employee)
    {

        $employee->refresh()->loadMissing('employeeDeductions');

        return $employee->employeeDeductions?->map(function (Deduction $deduction) {
            return [
                 str($deduction->name)->lower()->value() => $deduction->pivot->amount,
            ];
        })
            ->collapse()->all();

    }

    private function totalStatutoryDeductions(Employee $employee)
    {

        if (! $employee->should_pay_payee) {
            return 0;
        }


        return StatutoryDeduction::get()->sum(fn(StatutoryDeduction $deduction) => $deduction->getAmount($employee, $this->getGrossSalary($employee)));

    }
}
