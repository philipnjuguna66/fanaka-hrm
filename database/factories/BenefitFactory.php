<?php

namespace Database\Factories;

use App\Enums\Modes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Benefit>
 */
class BenefitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => sprintf('%s%s%s',$this->faker->randomLetter(),$this->faker->randomLetter(),$this->faker->randomLetter()),
            'code' => sprintf('%s%s%s',$this->faker->randomLetter(),$this->faker->randomLetter(),$this->faker->randomLetter()),
            'mode' => $this->faker->randomElement(array_keys(Modes::getKeyValueOptions())),
            'taxed_from_amount' => 0,
            'type' => $this->faker->randomElement(['fixed_amount','percentage']),
            'percentage_of' => 'basic_salary',
            'percentage_value' => $this->faker->randomNumber(2),
            'fixed_amount' => $this->faker->randomNumber(3),
        ];
    }

    public function taxable(): static
    {
        return $this->state(fn (array $attributes) => [
            'taxable' => true,
        ]);
    }

    public function nonCash(): static
    {
        return $this->state(fn (array $attributes) => [
            'non_cash' => true,
        ]);
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
