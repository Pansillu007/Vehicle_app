<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create demo user
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@vehicleapp.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        // Create vehicles for demo user
        $vehicles = [
            [
                'user_id' => $user->id,
                'name' => 'Daily Commuter',
                'make' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2022,
                'number_plate' => 'ABC-1234',
                'color' => 'Silver',
                'mileage' => 15000.50,
            ],
            [
                'user_id' => $user->id,
                'name' => 'Family SUV',
                'make' => 'Honda',
                'model' => 'CR-V',
                'year' => 2021,
                'number_plate' => 'XYZ-5678',
                'color' => 'Black',
                'mileage' => 28000.00,
            ],
            [
                'user_id' => $user->id,
                'name' => 'Weekend Sports Car',
                'make' => 'Mazda',
                'model' => 'MX-5',
                'year' => 2023,
                'number_plate' => 'SPR-9012',
                'color' => 'Red',
                'mileage' => 5000.00,
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            $vehicle = \App\Models\Vehicle::create($vehicleData);

            // Add service records for each vehicle
            if ($vehicle->name === 'Daily Commuter') {
                \App\Models\ServiceRecord::create([
                    'vehicle_id' => $vehicle->id,
                    'service_type' => 'Oil Change',
                    'description' => 'Regular 6-month oil change',
                    'cost' => 85.00,
                    'service_date' => now()->subMonths(2),
                    'service_provider' => 'Petronas Service Center',
                ]);

                \App\Models\ServiceRecord::create([
                    'vehicle_id' => $vehicle->id,
                    'service_type' => 'Tire Rotation',
                    'description' => 'Rotated all 4 tires',
                    'cost' => 50.00,
                    'service_date' => now()->subMonths(1),
                    'service_provider' => 'Bridgestone',
                ]);
            }

            if ($vehicle->name === 'Family SUV') {
                \App\Models\ServiceRecord::create([
                    'vehicle_id' => $vehicle->id,
                    'service_type' => 'Oil Change',
                    'description' => 'Full synthetic oil change',
                    'cost' => 120.00,
                    'service_date' => now()->subMonths(4),
                    'service_provider' => 'Honda Service Center',
                ]);

                \App\Models\ServiceRecord::create([
                    'vehicle_id' => $vehicle->id,
                    'service_type' => 'Brake Inspection',
                    'description' => 'Checked brake pads and rotors',
                    'cost' => 150.00,
                    'service_date' => now()->subMonths(3),
                    'service_provider' => 'Honda Service Center',
                ]);

                \App\Models\ServiceRecord::create([
                    'vehicle_id' => $vehicle->id,
                    'service_type' => 'Air Filter Replacement',
                    'description' => 'Replaced engine air filter',
                    'cost' => 45.00,
                    'service_date' => now()->subMonth(),
                    'service_provider' => 'AutoZone',
                ]);
            }
        }
    }
}
