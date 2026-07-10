<?php

namespace Tests\Feature;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_active_lab_test_can_be_reserved(): void
    {
        [$user] = $this->createPatientUser();
        $labTest = $this->createLabTest('active');

        $response = $this->actingAs($user)
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:00',
                'notes' => 'Pemeriksaan rutin.',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'patient_id' => $user->patient->id,
            'lab_test_id' => $labTest->id,
            'reservation_time' => '08:00',
            'status' => 'Menunggu',
        ]);
    }

    public function test_inactive_lab_test_is_rejected(): void
    {
        [$user] = $this->createPatientUser();
        $labTest = $this->createLabTest('inactive');

        $response = $this->actingAs($user)
            ->from(route('reservations.create'))
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:00',
            ]);

        $response
            ->assertRedirect(route('reservations.create'))
            ->assertSessionHasErrors('lab_test_id');

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_past_reservation_date_is_rejected(): void
    {
        [$user] = $this->createPatientUser();
        $labTest = $this->createLabTest('active');

        $response = $this->actingAs($user)
            ->from(route('reservations.create'))
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->subDay()->toDateString(),
                'reservation_time' => '08:00',
            ]);

        $response
            ->assertRedirect(route('reservations.create'))
            ->assertSessionHasErrors('reservation_date');

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_time_outside_available_schedule_is_rejected(): void
    {
        [$user] = $this->createPatientUser();
        $labTest = $this->createLabTest('active');

        $response = $this->actingAs($user)
            ->from(route('reservations.create'))
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '22:30',
            ]);

        $response
            ->assertRedirect(route('reservations.create'))
            ->assertSessionHasErrors('reservation_time');

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_notes_longer_than_500_characters_are_rejected(): void
    {
        [$user] = $this->createPatientUser();
        $labTest = $this->createLabTest('active');

        $response = $this->actingAs($user)
            ->from(route('reservations.create'))
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:00',
                'notes' => str_repeat('a', 501),
            ]);

        $response
            ->assertRedirect(route('reservations.create'))
            ->assertSessionHasErrors('notes');

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_reservation_uses_patient_owned_by_authenticated_user(): void
    {
        [$user, $patient] = $this->createPatientUser();
        $labTest = $this->createLabTest('active');

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'lab_test_id' => $labTest->id,
                'reservation_date' => now()->toDateString(),
                'reservation_time' => '08:30',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'patient_id' => $patient->id,
            'lab_test_id' => $labTest->id,
        ]);
    }

    private function createPatientUser(): array
    {
        $user = User::factory()->create([
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
            'full_name' => 'Pasien Validation',
            'nik' => '3374000000000001',
            'gender' => 'Laki-laki',
            'birth_date' => '2000-01-01',
            'phone' => '081234567890',
            'address' => 'Alamat testing',
            'blood_type' => 'O',
        ]);

        $user->setRelation('patient', $patient);

        return [$user, $patient];
    }

    private function createLabTest(string $status): LabTest
    {
        return LabTest::create([
            'name' => 'Tes ' . ucfirst($status),
            'slug' => 'tes-' . $status,
            'description' => 'Layanan untuk pengujian validasi.',
            'price' => 150000,
            'status' => $status,
        ]);
    }
}