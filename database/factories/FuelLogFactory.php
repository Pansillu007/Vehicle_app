<?php

namespace Database\Factories;

use App\Models\FuelLog;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class FuelLogFactory extends Factory
{
    protected $model = FuelLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vehicle_id' => Vehicle::factory(),
            'date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'amount' => $this->faker->randomFloat(2, 20, 60),
            'cost' => $this->faker->randomFloat(2, 50, 150),
            'odometer' => $this->faker->numberBetween(10000, 200000),
            'location' => $this->faker->city . ' Gas Station',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
