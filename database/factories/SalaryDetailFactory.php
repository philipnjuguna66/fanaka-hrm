<?php

namespace Database\Factories;

use App\Enums\EmploymentTerms;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalaryDetail>
 */
class SalaryDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'basic_salary' => $this->faker->numberBetween(150000, 1000000),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-1 month'),
            'terms_of_employment' => $this->faker->randomElement(array_keys(EmploymentTerms::getKeyValueOptions())),
        ];
    }

    public function withDisability(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_disability' => true,
            'disability_exemption_amount' => 1000,
            'exemption_certificate_no' => $this->faker->uuid(),
        ]);
    }

    public function basicSalary($salary = null): static
    {
        return $this->state(fn (array $attributes) => [
            'basic_salary' =>$salary ?? $this->faker->numberBetween(150000, 1000000),        ]);
    }

}
