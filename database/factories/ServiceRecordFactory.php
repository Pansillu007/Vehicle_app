<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'service_type' => $this->faker->randomElement([
                'Oil Change',
                'Tire Rotation',
                'Brake Service',
                'Engine Repair',
                'Transmission',
                'Battery Replacement',
                'Air Filter',
                'Inspection'
            ]),
            'description' => $this->faker->sentence,
            'cost' => $this->faker->randomFloat(2, 20, 500),
            'service_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'mileage' => $this->faker->numberBetween(1000, 150000),
            'service_provider' => $this->faker->company,
        ];
    }
}
