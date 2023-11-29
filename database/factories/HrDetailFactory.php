<?php

namespace Database\Factories;

use App\Models\BusinessUnit;
use App\Models\Department;
use App\Models\JobGrade;
use App\Models\JobTitle;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HrDetail>
 */
class HrDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_of_employment' => $this->faker->dateTimeBetween('-1 year', '-1 month'),
            'contract_start' => $this->faker->dateTimeBetween('-1 year', '-1 month'),
            'contract_end' => $this->faker->dateTimeBetween('+55 year', '+55 year'),
            'job_grade_id' => JobGrade::factory(),
            'job_title_id' => JobTitle::factory(),
            'region_id' => Region::factory(),
            'department_id' => Department::factory(),
            'reports_to_job_title_id' => JobTitle::factory(),
            'business_unit_id' => BusinessUnit::factory(),

        ];
    }

    public function boardDirector(): static
    {
        return $this->state(fn (array $attributes) => [
            'board_director' => true,
        ]);
    }
}
