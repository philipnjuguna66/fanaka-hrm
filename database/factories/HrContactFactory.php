<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactDetail>
 */
class HrContactFactory extends Factory
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
            'official_email' => $this->faker->email(),
            'personal_email' => $this->faker->email(),
            'country' => 'ke',
            'address' => $this->faker->address(),
            'office_phone_number' => $this->faker->e164PhoneNumber(),
            'office_phone_extension' => $this->faker->randomDigit(),
            'personal_phone_number' => $this->faker->e164PhoneNumber(),
            'city' => $this->faker->city(),
            'county' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'next_of_kin' => [
                [
                    'name' => $this->faker->firstName(),
                    'relation' => $this->faker->word(),
                    'phone' => $this->faker->e164PhoneNumber(),
                    'email' => $this->faker->email(),
                ]
            ],
            'social_links' => [
                [
                    'platform' => $this->faker->word(),
                    'url' => $this->faker->url(),
                ]
            ],
        ];
    }
}
