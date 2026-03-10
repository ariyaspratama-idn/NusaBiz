<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'brand' => $this->faker->randomElement(['Honda', 'Yamaha', 'Suzuki', 'Kawasaki']),
            'model' => $this->faker->word,
            'year' => $this->faker->year,
            'color' => $this->faker->colorName,
            'license_plate' => strtoupper($this->faker->bothify('B #### ???')),
            'current_odometer' => $this->faker->numberBetween(1000, 50000),
        ];
    }
}
