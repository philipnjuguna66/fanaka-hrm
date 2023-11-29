<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Benefit;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\HousingBenefit;
use App\Models\HousingBenefitType;
use App\Models\HrContact;
use App\Models\HrDetail;
use App\Models\JobGrade;
use App\Models\JobTitle;
use App\Models\SalaryDetail;
use App\Models\StatutoryDeduction;
use Database\Factories\JobGradeFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory()->create([
             'name' => 'Admin User',
             'email' => 'admin@admin.com',
         ]);

         $this->call(DeductionTypeSeeder::class);

         Deduction::factory()
            ->create([
                'deduction_type_id' =>4,
                'name' => 'SALARY ADVANCE',
                'type' => 'fixed_amount',
                'fixed_amount' => 0,
            ]);

         Deduction::factory()
                ->create([
                'deduction_type_id' =>6,
                'name' => 'Mobile Grant',
                'type' => 'percentage',
                'percentage_value' => 2.5,
            ]);

        Deduction::factory()
                ->create([
                'deduction_type_id' =>7,
                'name' => 'Laptop Grant',
                'type' => 'percentage',
                'percentage_value' => 2.5,
            ]);

        Deduction::factory()
                ->create([
                'deduction_type_id' =>8,
                'name' => 'Welfare',
                'type' => 'fixed_amount',
                'percentage_value' => 300,
            ]);

        Benefit::factory()->taxable()->fixed(20000)->create(['name'=>'House Allowance']);

        StatutoryDeduction::factory()->create([
            'name' => 'NSSF',
            'maximum'=> 700,
            'ranges' => [
                [
                    'min_range' =>0,
                    'max_range' =>3000,
                    'deduction'=> 180,
                ],
                [
                    'min_range' =>3001,
                    'max_range' =>4500,
                    'deduction'=> 270,
                ],
                [
                    'min_range' =>4501,
                    'max_range' =>6000,
                    'deduction'=> 360,
                ],
                [
                    'min_range' =>6001,
                    'max_range' =>10000,
                    'deduction'=> 600,
                ],
                [
                    'min_range' =>10001,
                    'max_range' =>14000,
                    'deduction'=> 840,
                ]
            ]
        ]);
        StatutoryDeduction::factory()->create([
            'name' => 'NHIF',
            'maximum'=> 1700,
            'ranges' => [
                [
                    'min_range' =>0,
                    'max_range' =>6000,
                    'type' =>"fixed_amount",
                    'deduction'=> 150,
                ],
                [
                    'min_range' =>6000,
                    'max_range' =>8000,
                    'type' =>"fixed_amount",
                    'deduction'=> 300,
                ],
                [
                    'min_range' =>8000,
                    'max_range' =>12000,
                    'type' =>"fixed_amount",
                    'deduction'=> 400,
                ],
                [
                    'min_range' =>12000,
                    'max_range' =>15000,
                    'type' =>"fixed_amount",
                    'deduction'=> 500,
                ],
                [
                    'min_range' =>15000,
                    'type' =>"fixed_amount",
                    'max_range' =>20000,
                    'deduction'=> 600,
                ],
                [
                    'min_range' =>20000,
                    'max_range' =>25000,
                    'type' =>"fixed_amount",
                    'deduction'=> 750,
                ],
                [
                    'min_range' =>25000,
                    'max_range' =>30000,
                    'type' =>"fixed_amount",
                    'deduction'=> 850,
                ],
                [
                    'min_range' =>30000,
                    'max_range' =>35000,
                    'type' =>"fixed_amount",
                    'deduction'=> 900,
                ],
                [
                    'min_range' =>35000,
                    'max_range' =>40000,
                    'type' =>"fixed_amount",
                    'deduction'=> 950,
                ],
                [
                    'min_range' =>40000,
                    'max_range' =>45000,
                    'type' =>"fixed_amount",
                    'deduction'=> 1000,
                ],
                [
                    'min_range' =>45000,
                    'max_range' =>50000,
                    'deduction'=> 1100,
                    'type' =>"fixed_amount",
                ],
                [
                    'min_range' =>50000,
                    'max_range' =>60000,
                    'deduction'=> 1200,
                    'type' =>"fixed_amount",
                ],
                [
                    'min_range' =>60000,
                    'max_range' =>70000,
                    'type' =>"fixed_amount",
                    'deduction'=> 1300,
                ],
                [
                    'min_range' =>70000,
                    'max_range' =>80000,
                    'type' =>"fixed_amount",
                    'deduction'=> 1400,
                ],
                [
                    'min_range' =>80000,
                    'max_range' =>90000,
                    'type' =>"fixed_amount",
                    'deduction'=> 1500,
                ],
                [
                    'min_range' =>90000,
                    'max_range' =>100000,
                    'type' =>"fixed_amount",
                    'deduction'=> 1600,
                ],
            ]
        ]);
        StatutoryDeduction::factory()->create([
            'name' => 'HOUSE LEVY',
            'maximum'=> 1700,
            'ranges' => [
                [
                    'min_range' =>0,
                    'max_range' =>99999999999999999999,
                    'type' =>"percentage",
                    'deduction'=> 1.5,
                ],
            ]
        ]);

        JobGrade::factory()->create([
            'title' => 'Entry Level'
        ]);
        JobGrade::factory()->create([
            'title' => 'Associate Level'
        ]);
       $mid = JobGrade::factory()->create([
            'title' => 'Mid-Level'
        ]);

        JobGrade::factory()->create([
            'title' => 'Senior Leve'
        ]);

        JobGrade::factory()->create([
            'title' => 'Supervisory/Managerial'
        ]);

        JobGrade::factory()->create([
            'title' => 'Executive/Leadership'
        ]);
        $cto = JobTitle::factory()->create([
            'label' => 'CT0'
        ]);

        Employee::factory(1)
             ->has(SalaryDetail::factory()->basicSalary(100000))
             ->has(HrDetail::factory()->for($mid)->for($cto))
             ->has(HrContact::factory())
             ->create();
    }
}
