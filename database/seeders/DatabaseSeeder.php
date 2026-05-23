<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
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

        // Seed Reminders
        \App\Models\Reminder::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle1->id,
            'title' => 'Insurance Renewal',
            'due_date' => now()->addDays(30),
            'type' => 'renewal',
            'status' => 'pending',
        ]);

        \App\Models\Reminder::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle2->id,
            'title' => 'Engine Tune-up',
            'due_date' => now()->addDays(15),
            'type' => 'service',
            'status' => 'pending',
        ]);

        \App\Models\Reminder::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle3->id,
            'title' => 'Tire Check',
            'due_date' => now()->addDays(5),
            'type' => 'maintenance',
            'status' => 'pending',
        ]);

        // Seed Fuel Logs
        \App\Models\FuelLog::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle1->id,
            'date' => now()->subDays(2),
            'amount' => 45.5,
            'cost' => 68.25,
            'odometer' => 45000,
            'location' => 'Shell Station',
        ]);

        // Seed Activity Logs
        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'created',
            'description' => 'Added a new vehicle: My Daily Driver',
            'subject_type' => 'App\Models\Vehicle',
            'subject_id' => $vehicle1->id,
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'updated',
            'description' => 'Updated service record for My Daily Driver',
            'subject_type' => 'App\Models\ServiceRecord',
            'subject_id' => 1,
        ]);

    }
}