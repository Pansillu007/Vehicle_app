<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(2, true),
            'make' => $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes']),
            'model' => $this->faker->randomElement(['Camry', 'Civic', 'Mustang', 'Corolla', 'Accord', 'F-150']),
            'year' => $this->faker->numberBetween(2015, 2024),
            'number_plate' => strtoupper($this->faker->unique()->bothify('???###')),
            'color' => $this->faker->safeColorName(),
            'mileage' => $this->faker->numberBetween(0, 100000),
            'fuel_type' => $this->faker->randomElement(['Petrol', 'Diesel', 'Electric', 'Hybrid']),
            'vin_number' => null,
        ];
    }
}
