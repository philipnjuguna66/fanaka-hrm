<?php

namespace Database\Factories;

use App\Enums\DeductionPercentageOf;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeductionType>
 */
class DeductionTypeFactory extends Factory
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
            'mode' =>'monthly',

        ];
    }

    public function taxAllowable(): static
    {
        return $this->state(fn (array $attributes) => [
            'tax_allowable' => true,
        ]);
    }

    public function nonTaxAllowable(): static
    {
        return $this->state(fn (array $attributes) => [
            'tax_allowable' => false,
        ]);
    }

    public function taxRelief(): static
    {
        return $this->state(fn (array $attributes) => [
            'tax_relief' => true,
        ]);
    }

    public function capped($limit = 0): static
    {
        return $this->state(fn (array $attributes) => [
            'capped' => true,
            'cap_limit' =>$limit,
        ]);
    }

}
