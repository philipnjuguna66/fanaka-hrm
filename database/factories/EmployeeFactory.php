<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement(['m','f']),
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
            'residential_status' =>$this->faker->randomElement(['resident','non-resident']),
            'legal_document_type' =>$this->faker->randomElement(['national_id','passport']),
            'legal_document_number' =>$this->faker->numberBetween(200000,40000000),
            'kra_pin_no' =>str('A')->upper()->append($this->faker->randomNumber(7))->append('W'),
            'nssf_no' =>$this->faker->randomNumber(5),
            'nhif_no' =>$this->faker->randomNumber(5),
            'marital_status' =>$this->faker->randomElement(['married','single']),
            'nationality' =>$this->faker->randomElement(['kenyan','tanzania']),
        ];
    }
}
