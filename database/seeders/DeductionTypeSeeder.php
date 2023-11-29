<?php

namespace Database\Seeders;

use App\Models\DeductionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeductionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $defined_contribution_fund = DeductionType::factory()
            ->taxAllowable()->capped(24000)
            ->create([
                'name' => "DefinedPensionContribution",
                'code' => 'dpc',
            ]);


        $mortgage_interest = DeductionType::factory()
            ->taxAllowable()->capped(25000)
            ->create([
                'name' => "MortgageInterestContribution",
                'code' => 'MIC'
            ]);

        $hop = DeductionType::factory()
            ->taxAllowable()
            ->capped(5000)
            ->create([
                'name' => "Home Ownership Plan",
                'code' => 'hop'
            ]);

        $normal_ded = DeductionType::factory()
            ->nonTaxAllowable()
            ->create([
                'name' => "Non Tax Allowable Deduction",
                'code' => 'ded'
            ]);


        $insurance = DeductionType::factory()
            ->taxRelief()
            ->capped(5000)
            ->create([
                'name' => "Insurance Relief",
                'code' => 'ir'
            ]);
        $fanakaDeductions = DeductionType::factory()
            ->taxAllowable()
            ->capped(5000)
            ->create([
                'name' => "Fanaka Deductions ",
                'code' => 'Fanaka-dedcutions'
            ]);

    }
}
