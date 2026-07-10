<?php

namespace Tests\Feature;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardStatisticsTest extends TestCase
{
    use RefreshDatabase;

protected function setUp(): void
{
    parent::setUp();

    $this->withoutVite();

    Date::setTestNow('2026-07-10 10:00:00');
}

protected function tearDown(): void
{
    Date::setTestNow();

    parent::tearDown();
}

    public function test_dashboard_displays_correct_reservation_statistics(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $labTest = $this->createLabTest(
            'Hematologi',
            'hematologi',
            'active'
        );

        $this->createLabTest(
            'Layanan Nonaktif',
            'layanan-nonaktif',
            'inactive'
        );

        $this->createReservation(
            'A001',
            '2026-07-10',
            'Menunggu',
            $labTest
        );

        $this->createReservation(
            'A002',
            '2026-07-06',
            'Terjadwal',
            $labTest
        );

        $this->createReservation(
            'A003',
            '2026-07-01',
            'Selesai',
            $labTest
        );

        $this->createReservation(
            'A004',
            '2026-06-30',
            'Dibatalkan',
            $labTest
        );

$this->actingAs($admin)
    ->get(route('admin.dashboard'))
    ->assertOk()
    ->assertViewHas('stats', function (array $stats) {
    return $stats['Total Reservasi'] === 4
        && $stats['Hari Ini'] === 1
        && $stats['Minggu Ini'] === 2
        && $stats['Bulan Ini'] === 3
        && $stats['Menunggu'] === 1
        && $stats['Terjadwal'] === 1
        && $stats['Diproses'] === 0
        && $stats['Selesai'] === 1
        && $stats['Dibatalkan'] === 1
        && $stats['Pasien'] === 4
        && $stats['Layanan Aktif'] === 1;
});
    }

    private function createLabTest(
        string $name,
        string $slug,
        string $status
    ): LabTest {
        return LabTest::create([
            'name' => $name,
            'slug' => $slug,
            'description' => 'Layanan statistik.',
            'price' => 150000,
            'status' => $status,
        ]);
    }

    private function createReservation(
        string $code,
        string $date,
        string $status,
        LabTest $labTest
    ): Reservation {
        $user = User::factory()->create([
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
            'full_name' => 'Pasien ' . $code,
            'nik' => '337400000000' . substr($code, 1),
            'gender' => 'Laki-laki',
            'birth_date' => '2000-01-01',
            'phone' => '081234567890',
            'address' => 'Alamat testing',
            'blood_type' => 'O',
        ]);

        return Reservation::create([
            'code' => $code,
            'patient_id' => $patient->id,
            'lab_test_id' => $labTest->id,
            'reservation_date' => $date,
            'reservation_time' => '08:00',
            'queue_number' => 'A-' . substr($code, 1),
            'status' => $status,
            'notes' => null,
        ]);
    }
}