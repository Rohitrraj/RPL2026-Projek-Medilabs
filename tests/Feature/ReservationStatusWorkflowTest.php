<?php

namespace Tests\Feature;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationStatusWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_admin_can_move_waiting_reservation_to_scheduled(): void
    {
        [$admin, $reservation] = $this->createScenario('Menunggu');

        $this->actingAs($admin)
            ->from(route('admin.reservations.show', $reservation))
            ->patch(
                route('admin.reservations.update-status', $reservation),
                ['status' => 'Terjadwal']
            )
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Terjadwal',
        ]);
    }

    public function test_admin_can_cancel_waiting_reservation(): void
    {
        [$admin, $reservation] = $this->createScenario('Menunggu');

        $this->actingAs($admin)
            ->patch(
                route('admin.reservations.update-status', $reservation),
                ['status' => 'Dibatalkan']
            )
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Dibatalkan',
        ]);
    }

    public function test_admin_can_move_scheduled_reservation_to_in_progress(): void
    {
        [$admin, $reservation] = $this->createScenario('Terjadwal');

        $this->actingAs($admin)
            ->patch(
                route('admin.reservations.update-status', $reservation),
                ['status' => 'Diproses']
            )
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Diproses',
        ]);
    }

    public function test_admin_can_complete_in_progress_reservation(): void
    {
        [$admin, $reservation] = $this->createScenario('Diproses');

        $this->actingAs($admin)
            ->patch(
                route('admin.reservations.update-status', $reservation),
                ['status' => 'Selesai']
            )
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_admin_cannot_skip_status_workflow(): void
    {
        [$admin, $reservation] = $this->createScenario('Menunggu');

        $this->actingAs($admin)
            ->from(route('admin.reservations.show', $reservation))
            ->patch(
                route('admin.reservations.update-status', $reservation),
                ['status' => 'Selesai']
            )
            ->assertRedirect(
                route('admin.reservations.show', $reservation)
            )
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Menunggu',
        ]);
    }

    public function test_completed_reservation_cannot_return_to_waiting(): void
    {
        [$admin, $reservation] = $this->createScenario('Selesai');

        $this->actingAs($admin)
            ->from(route('admin.reservations.show', $reservation))
            ->patch(
                route('admin.reservations.update-status', $reservation),
                ['status' => 'Menunggu']
            )
            ->assertRedirect(
                route('admin.reservations.show', $reservation)
            )
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_cancelled_reservation_cannot_be_reactivated(): void
    {
        [$admin, $reservation] = $this->createScenario('Dibatalkan');

        $this->actingAs($admin)
            ->from(route('admin.reservations.show', $reservation))
            ->patch(
                route('admin.reservations.update-status', $reservation),
                ['status' => 'Terjadwal']
            )
            ->assertRedirect(
                route('admin.reservations.show', $reservation)
            )
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Dibatalkan',
        ]);
    }

    public function test_saving_same_status_is_allowed(): void
    {
        [$admin, $reservation] = $this->createScenario('Menunggu');

        $this->actingAs($admin)
            ->patch(
                route('admin.reservations.update-status', $reservation),
                ['status' => 'Menunggu']
            )
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Menunggu',
        ]);
    }

    public function test_patient_can_cancel_waiting_reservation(): void
    {
        [, $reservation, $patientUser] = $this->createScenario('Menunggu');

        $this->actingAs($patientUser)
            ->patch(route('reservations.cancel', $reservation))
            ->assertRedirect(route('reservations.history'));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Dibatalkan',
        ]);
    }

    public function test_patient_can_cancel_scheduled_reservation(): void
    {
        [, $reservation, $patientUser] = $this->createScenario(
            'Terjadwal'
        );

        $this->actingAs($patientUser)
            ->patch(route('reservations.cancel', $reservation))
            ->assertRedirect(route('reservations.history'));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Dibatalkan',
        ]);
    }

    public function test_patient_cannot_cancel_in_progress_reservation(): void
    {
        [, $reservation, $patientUser] = $this->createScenario(
            'Diproses'
        );

        $this->actingAs($patientUser)
            ->from(route('reservations.history'))
            ->patch(route('reservations.cancel', $reservation))
            ->assertRedirect(route('reservations.history'))
            ->assertSessionHasErrors('reservation');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Diproses',
        ]);
    }

    public function test_patient_cannot_cancel_completed_reservation(): void
    {
        [, $reservation, $patientUser] = $this->createScenario(
            'Selesai'
        );

        $this->actingAs($patientUser)
            ->from(route('reservations.history'))
            ->patch(route('reservations.cancel', $reservation))
            ->assertRedirect(route('reservations.history'))
            ->assertSessionHasErrors('reservation');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_patient_cannot_cancel_already_cancelled_reservation(): void
    {
        [, $reservation, $patientUser] = $this->createScenario(
            'Dibatalkan'
        );

        $this->actingAs($patientUser)
            ->from(route('reservations.history'))
            ->patch(route('reservations.cancel', $reservation))
            ->assertRedirect(route('reservations.history'))
            ->assertSessionHasErrors('reservation');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'Dibatalkan',
        ]);
    }

    private function createScenario(string $status): array
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $patientUser = User::factory()->create([
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'full_name' => 'Pasien Workflow',
            'nik' => '3374000000000001',
            'gender' => 'Laki-laki',
            'birth_date' => '2000-01-01',
            'phone' => '081234567890',
            'address' => 'Alamat testing',
            'blood_type' => 'O',
        ]);

        $labTest = LabTest::create([
            'name' => 'Hematologi Lengkap',
            'slug' => 'hematologi-lengkap',
            'description' => 'Layanan pengujian status.',
            'price' => 150000,
            'status' => 'active',
        ]);

        $reservation = Reservation::create([
            'code' => 'A001',
            'patient_id' => $patient->id,
            'lab_test_id' => $labTest->id,
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '08:00',
            'queue_number' => 'A-01',
            'status' => $status,
            'notes' => null,
        ]);

        return [$admin, $reservation, $patientUser];
    }
}