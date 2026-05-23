<?php

namespace Database\Factories;

use App\Models\Reminder;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReminderFactory extends Factory
{
    protected $model = Reminder::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vehicle_id' => Vehicle::factory(),
            'title' => $this->faker->randomElement([
                'Oil Change',
                'Insurance Renewal',
                'Tire Rotation',
                'License Renewal',
                'Brake Inspection',
                'Annual Service'
            ]),
            'description' => $this->faker->sentence(),
            'due_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'type' => $this->faker->randomElement(['service', 'renewal', 'license', 'maintenance']),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
