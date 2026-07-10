<?php

namespace Tests\Feature;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCreationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_duplicate_active_reservation_is_rejected(): void
    {
        [$user, $patient] = $this->createPatientUser();
        $labTest = $this->createLabTest();

        $this->createReservation(
            $patient,
            $labTest,
            'A001',
            'Menunggu'
        );

        $response = $this->actingAs($user)
            ->from(route('reservations.create'))
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:00',
            ]);

        $response
            ->assertRedirect(route('reservations.create'))
            ->assertSessionHasErrors('reservation_time');

        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_duplicate_scheduled_reservation_is_rejected(): void
    {
        [$user, $patient] = $this->createPatientUser();
        $labTest = $this->createLabTest();

        $this->createReservation(
            $patient,
            $labTest,
            'A001',
            'Terjadwal'
        );

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:00',
            ])
            ->assertSessionHasErrors('reservation_time');

        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_duplicate_in_process_reservation_is_rejected(): void
    {
        [$user, $patient] = $this->createPatientUser();
        $labTest = $this->createLabTest();

        $this->createReservation(
            $patient,
            $labTest,
            'A001',
            'Diproses'
        );

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:00',
            ])
            ->assertSessionHasErrors('reservation_time');

        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_completed_reservation_does_not_block_new_reservation(): void
    {
        [$user, $patient] = $this->createPatientUser();
        $labTest = $this->createLabTest();

        $this->createReservation(
            $patient,
            $labTest,
            'A001',
            'Selesai'
        );

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:00',
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('reservations', 2);
    }

    public function test_cancelled_reservation_does_not_block_new_reservation(): void
    {
        [$user, $patient] = $this->createPatientUser();
        $labTest = $this->createLabTest();

        $this->createReservation(
            $patient,
            $labTest,
            'A001',
            'Dibatalkan'
        );

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:00',
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('reservations', 2);
    }

    public function test_same_service_with_different_time_is_allowed(): void
    {
        [$user, $patient] = $this->createPatientUser();
        $labTest = $this->createLabTest();

        $this->createReservation(
            $patient,
            $labTest,
            'A001',
            'Menunggu'
        );

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:30',
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('reservations', 2);
    }

    public function test_reservation_code_and_queue_number_remain_sequential(): void
    {
        [$user, $patient] = $this->createPatientUser();
        $labTest = $this->createLabTest();

        $this->createReservation(
            $patient,
            $labTest,
            'A001',
            'Menunggu'
        );

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->addDay()->toDateString(),
                'reservation_time' => '08:30',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'code' => 'A002',
            'queue_number' => 'A-02',
            'patient_id' => $patient->id,
        ]);
    }

    private function createPatientUser(): array
    {
        $user = User::factory()->create([
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
            'full_name' => 'Pasien Creation Flow',
            'nik' => '3374000000000001',
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
            'description' => 'Layanan pengujian workflow.',
            'price' => 150000,
            'status' => 'active',
        ]);
    }

    private function createReservation(
        Patient $patient,
        LabTest $labTest,
        string $code,
        string $status
    ): Reservation {
        return Reservation::create([
            'code' => $code,
            'patient_id' => $patient->id,
            'lab_test_id' => $labTest->id,
            'reservation_date' => now()->toDateString(),
            'reservation_time' => '08:00',
            'queue_number' => 'A-01',
            'status' => $status,
            'notes' => null,
        ]);
    }
}