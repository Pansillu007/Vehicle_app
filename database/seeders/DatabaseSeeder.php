<?php

namespace Database\Seeders;

<<<<<<< HEAD
=======
use App\Enums\UserRole;
>>>>>>> ec6237d (Third Week of Assignment small changes)
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
<<<<<<< HEAD
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
=======
        $admin = User::firstOrCreate(
            ['email' => 'admin@vehiclepro.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => UserRole::Admin,
            ]
        );
        if ($admin->role !== UserRole::Admin) {
            $admin->update(['role' => UserRole::Admin]);
        }

        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => UserRole::User,
            ]
        );

        if (\App\Models\Vehicle::where('user_id', $user->id)->exists()) {
            return;
        }

        $vehicle1 = \App\Models\Vehicle::create([
            'user_id' => $user->id,
            'name' => 'My Daily Driver',
            'make' => 'Toyota',
            'model' => 'Camry',
            'number_plate' => 'ABC-1234',
            'year' => 2020,
            'color' => 'Silver',
            'mileage' => 45000,
            'fuel_type' => 'Petrol',
            'vin_number' => '1TY123456789ABCDE',
            'next_service_due_date' => now()->addDays(10),
            'next_service_due_mileage' => 50000,
        ]);

        $vehicle2 = \App\Models\Vehicle::create([
            'user_id' => $user->id,
            'name' => 'Weekend Car',
            'make' => 'Ford',
            'model' => 'Mustang',
            'number_plate' => 'FAST-01',
            'year' => 2018,
            'color' => 'Red',
            'mileage' => 15000,
            'fuel_type' => 'Petrol',
            'vin_number' => '1FA123456789ABCDE',
        ]);

        $vehicle3 = \App\Models\Vehicle::create([
            'user_id' => $user->id,
            'name' => 'Family SUV',
            'make' => 'Honda',
            'model' => 'CR-V',
            'number_plate' => 'FAM-567',
            'year' => 2022,
            'color' => 'Blue',
            'mileage' => 20000,
            'fuel_type' => 'Hybrid',
            'vin_number' => 'JHM123456789ABCDE',
        ]);

        \App\Models\ServiceRecord::create([
            'vehicle_id' => $vehicle1->id,
            'service_type' => 'Oil Change',
            'description' => 'Regular synthetic oil change and filter replacement.',
            'service_date' => now()->subDays(30),
            'cost' => 85.50,
            'mileage' => 44500,
            'service_provider' => 'QuickLube Auto',
        ]);

        \App\Models\ServiceRecord::create([
            'vehicle_id' => $vehicle1->id,
            'service_type' => 'Brake Inspection',
            'description' => 'Replaced front brake pads and resurfaced rotors.',
            'service_date' => now()->subDays(120),
            'cost' => 320.00,
            'mileage' => 40000,
            'service_provider' => 'Toyota Dealership',
        ]);

        \App\Models\ServiceRecord::create([
            'vehicle_id' => $vehicle2->id,
            'service_type' => 'Tire Rotation',
            'description' => 'Rotated and balanced all four tires.',
            'service_date' => now()->subDays(60),
            'cost' => 45.00,
            'mileage' => 14000,
            'service_provider' => 'Discount Tires',
        ]);

        \App\Models\ServiceRecord::create([
            'vehicle_id' => $vehicle3->id,
            'service_type' => 'Annual Service',
            'description' => 'Comprehensive annual inspection, fluids topped off.',
            'service_date' => now()->subDays(15),
            'cost' => 150.00,
            'mileage' => 19500,
            'service_provider' => 'Honda Service Center',
        ]);

        \App\Models\ServiceRecord::create([
            'vehicle_id' => $vehicle1->id,
            'service_type' => 'Battery Replacement',
            'description' => 'Installed new 12V battery.',
            'service_date' => now()->subDays(5),
            'cost' => 120.00,
            'mileage' => 44900,
            'service_provider' => 'AutoZone',
        ]);
>>>>>>> ec6237d (Third Week of Assignment small changes)
    }
}
