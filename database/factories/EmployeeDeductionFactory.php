<?php

namespace Database\Factories;

use App\Models\Deduction;
use App\Models\DeductionType;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeDeduction>
 */
class EmployeeDeductionFactory extends Factory
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
            'deduction_id' => Deduction::factory(),
            'amount' => $this->faker->randomNumber(3)
        ];
    }
}
