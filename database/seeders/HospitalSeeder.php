<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;
use App\Models\OPDToken;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Departments
        $departments = [
            [
                'name' => 'General Medicine',
                'code' => 'GM',
                'description' => 'General physician consultation',
                'average_consultation_minutes' => 10,
            ],
            [
                'name' => 'Cardiology',
                'code' => 'CARD',
                'description' => 'Heart and cardiovascular care',
                'average_consultation_minutes' => 15,
            ],
            [
                'name' => 'Orthopedics',
                'code' => 'ORTHO',
                'description' => 'Bone and joint specialist',
                'average_consultation_minutes' => 12,
            ],
            [
                'name' => 'Pediatrics',
                'code' => 'PED',
                'description' => 'Child healthcare',
                'average_consultation_minutes' => 10,
            ],
            [
                'name' => 'Dermatology',
                'code' => 'DERM',
                'description' => 'Skin specialist',
                'average_consultation_minutes' => 8,
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        echo "\n✅ Departments created successfully!\n";

        // Create Doctor Users
        $doctors = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'doctor.sarah@hospital.com',
                'password' => bcrypt('password'),
                'role' => 'doctor',
                'department_id' => 1, // General Medicine
                'specialization' => 'General Physician',
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'doctor.michael@hospital.com',
                'password' => bcrypt('password'),
                'role' => 'doctor',
                'department_id' => 2, // Cardiology
                'specialization' => 'Cardiologist',
            ],
            [
                'name' => 'Dr. Emily Davis',
                'email' => 'doctor.emily@hospital.com',
                'password' => bcrypt('password'),
                'role' => 'doctor',
                'department_id' => 3, // Orthopedics
                'specialization' => 'Orthopedic Surgeon',
            ],
        ];

        foreach ($doctors as $doctor) {
            User::firstOrCreate(['email' => $doctor['email']], $doctor);
        }

        echo "✅ Doctor accounts created successfully!\n";

        // Create Staff Users
        $staff = [
            [
                'name' => 'Hospital Staff',
                'email' => 'staff@hospital.com',
                'password' => bcrypt('password'),
                'role' => 'staff',
                'department_id' => 1,
                'specialization' => null,
            ],
        ];

        foreach ($staff as $s) {
            User::firstOrCreate(['email' => $s['email']], $s);
        }

        echo "✅ Staff accounts created successfully!\n";

        // Create Patient Users
        $patients = [
            [
                'name' => 'John Doe',
                'email' => 'patient.john@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'patient.jane@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ],
            [
                'name' => 'Robert Brown',
                'email' => 'patient.robert@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ],
        ];

        $createdPatients = [];
        foreach ($patients as $patient) {
            $createdPatients[] = User::firstOrCreate(['email' => $patient['email']], $patient);
        }

        echo "✅ Patient accounts created successfully!\n";

        // Create Sample OPD Tokens for Today
        $today = today();
        
        $sampleTokens = [
            [
                'patient_id' => $createdPatients[0]->id,
                'department_id' => 1,
                'status' => 'completed',
                'date' => $today,
            ],
            [
                'patient_id' => $createdPatients[1]->id,
                'department_id' => 1,
                'status' => 'completed',
                'date' => $today,
            ],
            [
                'patient_id' => $createdPatients[2]->id,
                'department_id' => 1,
                'status' => 'ongoing',
                'date' => $today,
            ],
        ];

        // Note: Token numbers will be auto-generated by the model
        // We need to create them one by one to ensure proper numbering
        foreach ($sampleTokens as $tokenData) {
            OPDToken::create($tokenData);
        }

        echo "✅ Sample OPD tokens created successfully!\n";
        echo "\n🎉 Hospital OPD System seeded successfully!\n\n";
        echo "Login Credentials:\n";
        echo "==================\n";
        echo "Doctor (General Medicine): doctor.sarah@hospital.com / password\n";
        echo "Doctor (Cardiology): doctor.michael@hospital.com / password\n";
        echo "Doctor (Orthopedics): doctor.emily@hospital.com / password\n";
        echo "Staff: staff@hospital.com / password\n";
        echo "Patient 1: patient.john@example.com / password\n";
        echo "Patient 2: patient.jane@example.com / password\n";
        echo "Patient 3: patient.robert@example.com / password\n\n";
    }
}
