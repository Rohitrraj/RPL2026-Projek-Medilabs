<?php

namespace Tests\Feature;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationPrivacyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_is_redirected_to_login_when_opening_status_page(): void
    {
        $this->get(route('reservations.status', ['code' => 'A001']))
            ->assertRedirect(route('login'));
    }

    public function test_login_returns_patient_to_intended_status_page(): void
    {
        $user = User::factory()->create([
            'role' => 'patient',
            'password' => bcrypt('password'),
        ]);

        $statusUrl = route('reservations.status', ['code' => 'A001']);

        $this->get($statusUrl)
            ->assertRedirect(route('login'));

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect($statusUrl);
    }

    public function test_patient_can_search_own_reservation_status(): void
    {
        [$user, $patient] = $this->createPatientUser(
            'owner@example.com',
            '3374000000000001'
        );

        $labTest = $this->createLabTest(
            'Layanan Milik Sendiri',
            'layanan-milik-sendiri'
        );

        $this->createReservation($patient, $labTest, 'A001');

        $this->actingAs($user)
            ->get(route('reservations.status', ['code' => 'a001']))
            ->assertOk()
            ->assertSee('Detail Reservasi A001')
            ->assertSee('Layanan Milik Sendiri');
    }

    public function test_patient_cannot_view_another_patient_status_by_code(): void
    {
        [, $ownerPatient] = $this->createPatientUser(
            'owner@example.com',
            '3374000000000001'
        );

        [$otherUser] = $this->createPatientUser(
            'other@example.com',
            '3374000000000002'
        );

        $labTest = $this->createLabTest(
            'Layanan Rahasia Pemilik',
            'layanan-rahasia-pemilik'
        );

        $this->createReservation($ownerPatient, $labTest, 'A007');

        $this->actingAs($otherUser)
            ->get(route('reservations.status', ['code' => 'A007']))
            ->assertOk()
            ->assertSee('Reservasi tidak ditemukan')
            ->assertSee('pada akun Anda')
            ->assertDontSee('Layanan Rahasia Pemilik');
    }

    public function test_invalid_code_does_not_fallback_to_latest_reservation(): void
    {
        [$user, $patient] = $this->createPatientUser(
            'owner@example.com',
            '3374000000000001'
        );

        $labTest = $this->createLabTest(
            'Layanan Terbaru Pemilik',
            'layanan-terbaru-pemilik'
        );

        $this->createReservation($patient, $labTest, 'A001');

        $this->actingAs($user)
            ->get(route('reservations.status', ['code' => 'A999']))
            ->assertOk()
            ->assertSee('Reservasi tidak ditemukan')
            ->assertDontSee('Layanan Terbaru Pemilik');
    }

    public function test_home_status_cta_sends_guest_to_login_and_patient_to_status(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('login', ['reason' => 'status']), false)
            ->assertSee('Masuk untuk Cek Status');

        $patientUser = User::factory()->create([
            'role' => 'patient',
        ]);

        $this->actingAs($patientUser)
            ->get(route('home'))
            ->assertOk()
            ->assertSee(route('reservations.status'), false)
            ->assertSee('Cek Status Reservasi');
    }

    private function createPatientUser(string $email, string $nik): array
    {
        $user = User::factory()->create([
            'email' => $email,
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
            'full_name' => 'Pasien Privacy',
            'nik' => $nik,
            'gender' => 'Laki-laki',
            'birth_date' => '2000-01-01',
            'phone' => '081234567890',
            'address' => 'Alamat testing',
            'blood_type' => 'O',
        ]);

        return [$user, $patient];
    }

    private function createLabTest(string $name, string $slug): LabTest
    {
        return LabTest::create([
            'name' => $name,
            'slug' => $slug,
            'description' => 'Layanan pengujian privasi reservasi.',
            'price' => 150000,
            'status' => 'active',
        ]);
    }

    private function createReservation(
        Patient $patient,
        LabTest $labTest,
        string $code
    ): Reservation {
        return Reservation::create([
            'code' => $code,
            'patient_id' => $patient->id,
            'lab_test_id' => $labTest->id,
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '08:00:00',
            'queue_number' => 'A-01',
            'status' => 'Menunggu',
            'notes' => null,
        ]);
    }
}
