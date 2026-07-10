<?php

namespace Tests\Feature;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReservationFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_admin_can_search_reservation_by_code(): void
    {
        $admin = $this->createAdmin();

        $firstReservation = $this->createReservation(
            code: 'A001',
            patientName: 'Pasien Pertama'
        );

        $secondReservation = $this->createReservation(
            code: 'A002',
            patientName: 'Pasien Kedua'
        );

$this->actingAs($admin)
    ->get(route('admin.reservations.manage', [
        'code' => 'A001',
    ]))
    ->assertOk()
    ->assertViewHas('reservations', function ($reservations) use (
        $firstReservation,
        $secondReservation
    ) {
        return $reservations->contains('id', $firstReservation->id)
            && ! $reservations->contains('id', $secondReservation->id);
    });
    }

    public function test_admin_can_search_reservation_by_patient_name(): void
    {
        $admin = $this->createAdmin();

        $this->createReservation(
            code: 'A001',
            patientName: 'Rohit Raj'
        );

        $this->createReservation(
            code: 'A002',
            patientName: 'Budi Santoso'
        );

        $this->actingAs($admin)
            ->get(route('admin.reservations.manage', [
                'patient' => 'Rohit',
            ]))
            ->assertOk()
            ->assertSee('Rohit Raj')
            ->assertDontSee('Budi Santoso');
    }

    public function test_admin_can_filter_reservations_by_status(): void
    {
        $admin = $this->createAdmin();

        $this->createReservation(
            code: 'A001',
            patientName: 'Pasien Menunggu',
            status: 'Menunggu'
        );

        $this->createReservation(
            code: 'A002',
            patientName: 'Pasien Selesai',
            status: 'Selesai'
        );

$this->actingAs($admin)
    ->get(route('admin.reservations.manage', [
        'status' => 'Menunggu',
    ]))
    ->assertOk()
    ->assertViewHas('reservations', function ($reservations) {
        return $reservations->pluck('code')->all() === ['A001'];
    });
    }

    public function test_admin_can_filter_reservations_by_lab_test(): void
    {
        $admin = $this->createAdmin();

        $hematology = $this->createLabTest(
            'Hematologi Lengkap',
            'hematologi-lengkap'
        );

        $urinalysis = $this->createLabTest(
            'Urinalisis',
            'urinalisis'
        );

        $this->createReservation(
            code: 'A001',
            patientName: 'Pasien Hematologi',
            labTest: $hematology
        );

        $this->createReservation(
            code: 'A002',
            patientName: 'Pasien Urinalisis',
            labTest: $urinalysis
        );

        $this->actingAs($admin)
            ->get(route('admin.reservations.manage', [
                'lab_test_id' => $hematology->id,
            ]))
            ->assertOk()
            ->assertSee('A001')
            ->assertDontSee('A002');
    }

    public function test_admin_can_filter_reservations_by_date(): void
    {
        $admin = $this->createAdmin();

        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $this->createReservation(
            code: 'A001',
            patientName: 'Pasien Hari Ini',
            reservationDate: $today
        );

        $this->createReservation(
            code: 'A002',
            patientName: 'Pasien Besok',
            reservationDate: $tomorrow
        );

$this->actingAs($admin)
    ->get(route('admin.reservations.manage', [
        'reservation_date' => $today,
    ]))
    ->assertOk()
    ->assertViewHas('reservations', function ($reservations) {
        return $reservations->pluck('code')->all() === ['A001'];
    });
    }

    public function test_admin_can_combine_multiple_filters(): void
    {
        $admin = $this->createAdmin();

        $hematology = $this->createLabTest(
            'Hematologi Lengkap',
            'hematologi-lengkap'
        );

        $this->createReservation(
            code: 'A001',
            patientName: 'Rohit Raj',
            status: 'Menunggu',
            labTest: $hematology
        );

        $this->createReservation(
            code: 'A002',
            patientName: 'Rohit Lain',
            status: 'Selesai',
            labTest: $hematology
        );

        $this->actingAs($admin)
            ->get(route('admin.reservations.manage', [
                'patient' => 'Rohit',
                'status' => 'Menunggu',
                'lab_test_id' => $hematology->id,
            ]))
            ->assertOk()
            ->assertSee('A001')
            ->assertDontSee('A002');
    }

    public function test_admin_can_sort_reservations_from_oldest(): void
    {
        $admin = $this->createAdmin();

        $this->createReservation(
            code: 'A002',
            patientName: 'Pasien Besok',
            reservationDate: now()->addDay()->toDateString()
        );

        $this->createReservation(
            code: 'A001',
            patientName: 'Pasien Hari Ini',
            reservationDate: now()->toDateString()
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.reservations.manage', [
                'sort' => 'oldest',
            ]));

        $response
            ->assertOk()
            ->assertSeeInOrder([
                'A001',
                'A002',
            ]);
    }

    public function test_admin_can_sort_reservations_from_latest(): void
    {
        $admin = $this->createAdmin();

        $this->createReservation(
            code: 'A001',
            patientName: 'Pasien Hari Ini',
            reservationDate: now()->toDateString()
        );

        $this->createReservation(
            code: 'A002',
            patientName: 'Pasien Besok',
            reservationDate: now()->addDay()->toDateString()
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.reservations.manage', [
                'sort' => 'latest',
            ]));

        $response
            ->assertOk()
            ->assertSeeInOrder([
                'A002',
                'A001',
            ]);
    }

    public function test_reservations_are_paginated_ten_per_page(): void
    {
        $admin = $this->createAdmin();

        for ($index = 1; $index <= 11; $index++) {
            $this->createReservation(
                code: 'A' . str_pad(
                    (string) $index,
                    3,
                    '0',
                    STR_PAD_LEFT
                ),
                patientName: 'Pasien ' . $index,
                reservationDate: now()
                    ->addDays($index)
                    ->toDateString()
            );
        }

        $firstPage = $this->actingAs($admin)
            ->get(route('admin.reservations.manage'));

        $firstPage
            ->assertOk()
            ->assertViewHas(
                'reservations',
                function ($reservations) {
                    return $reservations->perPage() === 10
                        && $reservations->total() === 11
                        && $reservations->count() === 10;
                }
            );

        $secondPage = $this->actingAs($admin)
            ->get(route('admin.reservations.manage', [
                'page' => 2,
            ]));

        $secondPage
            ->assertOk()
            ->assertViewHas(
                'reservations',
                function ($reservations) {
                    return $reservations->currentPage() === 2
                        && $reservations->count() === 1;
                }
            );
    }

    public function test_patient_cannot_access_admin_reservation_management(): void
    {
        $patient = User::factory()->create([
            'role' => 'patient',
        ]);

        $this->actingAs($patient)
            ->get(route('admin.reservations.manage'))
            ->assertForbidden();
    }

    public function test_guest_cannot_access_admin_reservation_management(): void
    {
        $this->get(route('admin.reservations.manage'))
            ->assertRedirect(route('login'));
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    private function createLabTest(
        string $name,
        string $slug
    ): LabTest {
        return LabTest::create([
            'name' => $name,
            'slug' => $slug,
            'description' => 'Layanan pengujian filter admin.',
            'price' => 150000,
            'status' => 'active',
        ]);
    }

    private function createReservation(
        string $code,
        string $patientName,
        string $status = 'Menunggu',
        ?LabTest $labTest = null,
        ?string $reservationDate = null
    ): Reservation {
        $patientUser = User::factory()->create([
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'full_name' => $patientName,
            'nik' => $this->uniqueNik(),
            'gender' => 'Laki-laki',
            'birth_date' => '2000-01-01',
            'phone' => '081234567890',
            'address' => 'Alamat testing',
            'blood_type' => 'O',
        ]);

        $labTest ??= $this->createLabTest(
            'Tes ' . $code,
            'tes-' . strtolower($code)
        );

        return Reservation::create([
            'code' => $code,
            'patient_id' => $patient->id,
            'lab_test_id' => $labTest->id,
            'reservation_date' => $reservationDate
                ?? now()->toDateString(),
            'reservation_time' => '08:00',
            'queue_number' => 'A-' . substr($code, 1),
            'status' => $status,
            'notes' => null,
        ]);
    }

    private function uniqueNik(): string
    {
        static $sequence = 0;

        $sequence++;

        return '3374000000'
            . str_pad(
                (string) $sequence,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}