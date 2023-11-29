<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarBenefit>
 */
class CarBenefitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'car_reg_no' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'make' => $this->faker->randomElement(
                [
                    'Pick Ups, Panel Vans Uncovered',
                    'Saloon Hatch Backs and Estates',
                    'Land Rovers/ Cruisers(excludes Range Rovers and vehicles of similar nature)'
                ]
            ),
            'body_type' => $this->faker
                ->randomElement(['Saloon', 'Hatchback', 'MPV', 'SUV', 'Coupe', 'Convertible', 'Cabriolet']),
            'cc_rating' => $this->faker->randomNumber(4)
        ];
    }

    public function owned($cost = 0): static
    {
        return $this->state(fn (array $attributes) => [
            'type_of_car_cost' => 'owned',
            'cost_of_owned_car' => $this->faker->randomNumber(3),
            'commissioner_rate' => $this->faker->randomNumber(3),
            'benefit_rate' => $this->faker->randomNumber(2),
        ]);
    }

    public function hired( ): static
    {
        return $this->state(fn (array $attributes) => [
            'type_of_car_cost' => 'hired',
            'cost_of_hiring' => $this->faker->randomNumber(4),
        ]);
    }
}
