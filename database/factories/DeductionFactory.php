<?php

namespace Database\Factories;

use App\Enums\DeductionPercentageOf;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deduction>
 */
class DeductionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'deduction_type_id' =>\App\Models\DeductionType::factory(),
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['fixed_amount','percentage']),
            'percentage_of' => 'basic_salary',
        ];
    }


    public function fixed($value = null): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'fixed_amount',
            'fixed_amount' => $value ?? $this->faker->randomNumber(4),
        ]);
    }

    public function percentage($percentage = null): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'percentage',
            'percentage_value' =>  $percentage ?? $this->faker->randomNumber(2)
        ]);
    }
}
