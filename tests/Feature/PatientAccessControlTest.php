<?php

namespace Tests\Feature;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_cannot_access_private_patient_routes(): void
    {
        [$user, $patient] = $this->createPatientUser(
            'owner@example.com',
            '3374000000000001'
        );

        $labTest = $this->createLabTest();

        $reservation = $this->createReservation(
            $patient,
            $labTest,
            'A001'
        );

        $this->get(route('profile.show'))
            ->assertRedirect(route('login'));

        $this->get(route('patients.create'))
            ->assertRedirect(route('login'));

        $this->get(route('reservations.create'))
            ->assertRedirect(route('login'));

        $this->get(route('reservations.result', $reservation))
            ->assertRedirect(route('login'));

        $this->get(route('reservations.history'))
            ->assertRedirect(route('login'));

        $this->patch(route('reservations.cancel', $reservation))
            ->assertRedirect(route('login'));
    }

    public function test_patient_can_view_own_reservation_result(): void
    {
        [$user, $patient] = $this->createPatientUser(
            'owner@example.com',
            '3374000000000001'
        );

        $labTest = $this->createLabTest();

        $reservation = $this->createReservation(
            $patient,
            $labTest,
            'A001'
        );

        $this->actingAs($user)
            ->get(route('reservations.result', $reservation))
            ->assertOk()
            ->assertSee('A001');
    }

    public function test_patient_cannot_view_another_patient_reservation(): void
    {
        [$owner, $ownerPatient] = $this->createPatientUser(
            'owner@example.com',
            '3374000000000001'
        );

        [$otherUser] = $this->createPatientUser(
            'other@example.com',
            '3374000000000002'
        );

        $labTest = $this->createLabTest();

        $reservation = $this->createReservation(
            $ownerPatient,
            $labTest,
            'A001'
        );

        $this->actingAs($otherUser)
            ->get(route('reservations.result', $reservation))
            ->assertForbidden();
    }

    public function test_history_only_contains_current_patient_reservations(): void
    {
        [$owner, $ownerPatient] = $this->createPatientUser(
            'owner@example.com',
            '3374000000000001'
        );

        [, $otherPatient] = $this->createPatientUser(
            'other@example.com',
            '3374000000000002'
        );

        $labTest = $this->createLabTest();

        $this->createReservation(
            $ownerPatient,
            $labTest,
            'A001'
        );

        $this->createReservation(
            $otherPatient,
            $labTest,
            'A002'
        );

        $this->actingAs($owner)
            ->get(route('reservations.history'))
            ->assertOk()
            ->assertSee('A001')
            ->assertDontSee('A002');
    }

    public function test_patient_cannot_cancel_another_patient_reservation(): void
    {
        [, $ownerPatient] = $this->createPatientUser(
            'owner@example.com',
            '3374000000000001'
        );

        [$otherUser] = $this->createPatientUser(
            'other@example.com',
            '3374000000000002'
        );

        $labTest = $this->createLabTest();

        $reservation = $this->createReservation(
            $ownerPatient,
            $labTest,
            'A001'
        );

        $this->actingAs($otherUser)
            ->patch(route('reservations.cancel', $reservation))
            ->assertForbidden();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
        ]);
    }

    public function test_patient_can_cancel_own_reservation(): void
    {
        [$owner, $ownerPatient] = $this->createPatientUser(
            'owner@example.com',
            '3374000000000001'
        );

        $labTest = $this->createLabTest();

        $reservation = $this->createReservation(
            $ownerPatient,
            $labTest,
            'A001'
        );

        $this->actingAs($owner)
            ->patch(route('reservations.cancel', $reservation))
            ->assertRedirect(route('reservations.history'));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Dibatalkan',
        ]);
    }

    public function test_profile_without_patient_does_not_leak_other_reservations(): void
    {
        $userWithoutPatient = User::factory()->create([
            'email' => 'without-patient@example.com',
            'role' => 'patient',
        ]);

        [, $otherPatient] = $this->createPatientUser(
            'other@example.com',
            '3374000000000002'
        );

        $labTest = $this->createLabTest();

        $this->createReservation(
            $otherPatient,
            $labTest,
            'A999'
        );

        $this->actingAs($userWithoutPatient)
            ->get(route('profile.show'))
            ->assertOk()
            ->assertDontSee('A999');
    }

    private function createPatientUser(
        string $email,
        string $nik
    ): array {
        $user = User::factory()->create([
            'email' => $email,
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
            'full_name' => 'Pasien Testing',
            'nik' => $nik,
            'gender' => 'Laki-laki',
            'birth_date' => '2000-01-01',
            'phone' => '081234567890',
            'address' => 'Alamat testing',
            'blood_type' => 'O',
        ]);

        return [$user, $patient];
    }

    private function createLabTest(): LabTest
    {
        return LabTest::create([
            'name' => 'Hematologi Lengkap',
            'slug' => 'hematologi-lengkap',
            'description' => 'Layanan untuk pengujian akses.',
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