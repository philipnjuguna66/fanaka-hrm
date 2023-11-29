<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\HousingBenefit;
use App\Models\HousingBenefitType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HousingBenefit>
 */
class HousingBenefitFactory extends Factory
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
            'housing_benefit_type_id' => HousingBenefitType::factory(),
            'fair_market_rent_value' => $this->faker->randomNumber(3),
            'rent_recovered' => $this->faker->randomNumber(3),
        ];
    }
    public function usingGrossPercentage(): static
    {
        return $this->state(fn (array $attributes) => [
            'rate' => $this->faker->randomNumber(2),
        ]);
    }

    public function usingActualRentValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'actual_rent_value' => $this->faker->randomNumber(3)
        ]);
    }

}
