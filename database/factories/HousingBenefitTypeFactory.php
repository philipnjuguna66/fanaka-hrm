<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HousingBenefitType>
 */
class HousingBenefitTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'calculation_type' => $this->faker->randomElement(['rate','actual_rent_value'])
        ];
    }

    public function usingGrossPercentage(): static
    {
        return $this->state(fn (array $attributes) => [
            'calculation_type' => 'rate',
        ]);
    }

    public function usingActualRentValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'calculation_type' => 'actual_rent_value',
        ]);
    }

}
