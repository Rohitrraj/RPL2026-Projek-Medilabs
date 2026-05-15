<?php

namespace Database\Seeders;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            LabTestSeeder::class,
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@medilabs.test'],
            [
                'name' => 'Admin MediLabs',
                'phone' => '080000000001',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        $patientUser = User::updateOrCreate(
            ['email' => 'rohit@example.com'],
            [
                'name' => 'Rohit Raj',
                'phone' => '081234567890',
                'role' => 'patient',
                'password' => Hash::make('password123'),
            ]
        );

        $patient = Patient::updateOrCreate(
            ['nik' => '3402000000000001'],
            [
                'user_id' => $patientUser->id,
                'full_name' => 'Rohit Raj',
                'gender' => 'Laki-laki',
                'birth_date' => '2003-05-15',
                'phone' => '081234567890',
                'address' => 'Yogyakarta',
                'blood_type' => 'O',
            ]
        );

        $hematology = LabTest::where('slug', 'hematologi-lengkap')->first();

        if ($hematology) {
            Reservation::updateOrCreate(
                ['code' => 'A01000'],
                [
                    'patient_id' => $patient->id,
                    'lab_test_id' => $hematology->id,
                    'reservation_date' => '2026-05-15',
                    'reservation_time' => '09:00',
                    'queue_number' => 'A-01',
                    'status' => 'Terjadwal',
                    'notes' => 'Pemeriksaan rutin.',
                ]
            );
        }
    }
}
