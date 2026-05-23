<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        $actions = ['created', 'updated', 'deleted', 'viewed'];
        $subjects = ['Vehicle', 'ServiceRecord', 'Reminder'];

        return [
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement($actions),
            'description' => $this->faker->sentence(),
            'subject_type' => 'App\\Models\\' . $this->faker->randomElement($subjects),
            'subject_id' => $this->faker->numberBetween(1, 100),
            'properties' => ['browser' => 'Chrome', 'ip' => $this->faker->ipv4],
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
