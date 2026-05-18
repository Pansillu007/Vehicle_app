<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ServiceRecord;
use Illuminate\Support\Facades\Hash;

class VehicleManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@vehicleapp.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Create vehicles
        $vehicles = [
            [
                'name' => 'Daily Commuter',
                'make' => 'Toyota',
                'model' => 'Vios',
                'year' => 2020,
                'number_plate' => 'BVA 1234',
                'color' => 'Silver',
                'mileage' => 45000.00,
                'fuel_type' => 'Petrol',
            ],
            [
                'name' => 'Family SUV',
                'make' => 'Honda',
                'model' => 'CR-V',
                'year' => 2019,
                'number_plate' => 'BVA 5678',
                'color' => 'White',
                'mileage' => 62000.00,
                'fuel_type' => 'Petrol',
            ],
            [
                'name' => 'Business Car',
                'make' => 'BMW',
                'model' => '3 Series',
                'year' => 2021,
                'number_plate' => 'BVA 9012',
                'color' => 'Black',
                'mileage' => 28000.00,
                'fuel_type' => 'Diesel',
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            $vehicle = Vehicle::create(array_merge($vehicleData, [
                'user_id' => $user->id,
            ]));

            // Create service records for each vehicle
            $this->createServiceRecords($vehicle);
        }

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@vehicleapp.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }

    /**
     * Create service records for a vehicle
     */
    private function createServiceRecords(Vehicle $vehicle): void
    {
        $serviceRecords = [
            [
                'service_type' => 'Oil Change',
                'description' => 'Regular oil change with synthetic oil',
                'cost' => 150.00,
                'service_date' => now()->subMonths(2),
                'service_provider' => 'Toyota Service Center',
            ],
            [
                'service_type' => 'Brake Pad Replacement',
                'description' => 'Front brake pads replaced',
                'cost' => 350.00,
                'service_date' => now()->subMonths(4),
                'service_provider' => 'AutoPro Workshop',
            ],
            [
                'service_type' => 'Tire Rotation',
                'description' => 'Rotated all tires and checked pressure',
                'cost' => 80.00,
                'service_date' => now()->subMonths(1),
                'service_provider' => 'Tire Express',
            ],
            [
                'service_type' => 'Air Filter Replacement',
                'description' => 'Engine air filter replaced',
                'cost' => 65.00,
                'service_date' => now()->subMonths(3),
                'service_provider' => 'Toyota Service Center',
            ],
            [
                'service_type' => 'Battery Check',
                'description' => 'Battery tested and terminals cleaned',
                'cost' => 50.00,
                'service_date' => now()->subMonths(5),
                'service_provider' => 'Battery World',
            ],
        ];

        foreach ($serviceRecords as $record) {
            $vehicle->serviceRecords()->create($record);
        }
    }
}
