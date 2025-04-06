<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['I', 'B']);
        $name = $type == 'I' ? $this->faker->name() : $this->faker->company();
        $formattedName = strtolower(str_replace(' ', '', $name));

        return [
            'name' => $name,
            'type' => $type,
            'email' => $formattedName . '@gmail.com',
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->country(),
            'postal_code' => $this->faker->postcode(),
        ];
    }
}

