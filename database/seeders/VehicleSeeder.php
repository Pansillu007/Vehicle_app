<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ServiceRecord;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create regular users
        $user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Create vehicles for user 1
        $vehicle1 = Vehicle::create([
            'user_id' => $user1->id,
            'name' => 'Daily Driver',
            'make' => 'Toyota',
            'model' => 'Camry',
            'year' => 2020,
            'number_plate' => 'ABC-1234',
            'color' => 'Silver',
            'mileage' => 45000.50,
        ]);

        $vehicle2 = Vehicle::create([
            'user_id' => $user1->id,
            'name' => 'Weekend Car',
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2019,
            'number_plate' => 'XYZ-5678',
            'color' => 'Blue',
            'mileage' => 62000.00,
        ]);

        // Create vehicles for user 2
        $vehicle3 = Vehicle::create([
            'user_id' => $user2->id,
            'name' => 'Family SUV',
            'make' => 'Ford',
            'model' => 'Explorer',
            'year' => 2021,
            'number_plate' => 'DEF-9012',
            'color' => 'Black',
            'mileage' => 30000.00,
        ]);

        // Create service records for vehicle 1
        ServiceRecord::create([
            'vehicle_id' => $vehicle1->id,
            'service_type' => 'Oil Change',
            'description' => 'Regular oil change with synthetic oil',
            'cost' => 75.00,
            'service_date' => '2024-01-15',
            'service_provider' => 'Quick Lube Shop',
        ]);

        ServiceRecord::create([
            'vehicle_id' => $vehicle1->id,
            'service_type' => 'Tire Rotation',
            'description' => 'Rotated all four tires and checked pressure',
            'cost' => 50.00,
            'service_date' => '2024-02-20',
            'service_provider' => 'Tire Center',
        ]);

        ServiceRecord::create([
            'vehicle_id' => $vehicle1->id,
            'service_type' => 'Brake Service',
            'description' => 'Replaced front brake pads',
            'cost' => 250.00,
            'service_date' => '2024-03-10',
            'service_provider' => 'Auto Repair Shop',
        ]);

        // Create service records for vehicle 2
        ServiceRecord::create([
            'vehicle_id' => $vehicle2->id,
            'service_type' => 'Inspection',
            'description' => 'Annual vehicle inspection',
            'cost' => 100.00,
            'service_date' => '2024-01-05',
            'service_provider' => 'Inspection Station',
        ]);

        // Create service records for vehicle 3
        ServiceRecord::create([
            'vehicle_id' => $vehicle3->id,
            'service_type' => 'Oil Change',
            'description' => 'First oil change',
            'cost' => 85.00,
            'service_date' => '2024-02-01',
            'service_provider' => 'Dealership',
        ]);

        ServiceRecord::create([
            'vehicle_id' => $vehicle3->id,
            'service_type' => 'Battery Replacement',
            'description' => 'Replaced old battery with new one',
            'cost' => 150.00,
            'service_date' => '2024-03-15',
            'service_provider' => 'Auto Parts Store',
        ]);
    }
}
