<?php

namespace Tests\Feature;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReservationExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(
            Carbon::parse('2026-07-10 10:00:00')
        );
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_admin_can_export_monthly_reservations(): void
    {
        $admin = $this->createUser('admin');
        $labTest = $this->createLabTest();

        $this->createReservation(
            'A001',
            '2026-07-10',
            $labTest
        );

        $this->createReservation(
            'A002',
            '2026-06-30',
            $labTest
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.reservations.export', [
                'period' => 'month',
            ]));

        $response
            ->assertOk()
            ->assertHeader(
                'content-type',
                'text/csv; charset=UTF-8'
            );

        $contentDisposition = $response->headers->get(
            'content-disposition'
        );

        $this->assertStringContainsString(
            'rekap-reservasi-bulanan-2026-07.csv',
            $contentDisposition
        );

        $content = $response->streamedContent();

        $this->assertStringContainsString('A001', $content);
        $this->assertStringNotContainsString('A002', $content);
    }

    public function test_admin_can_export_custom_date_range(): void
    {
        $admin = $this->createUser('admin');
        $labTest = $this->createLabTest();

        $this->createReservation(
            'A001',
            '2026-07-05',
            $labTest
        );

        $this->createReservation(
            'A002',
            '2026-07-20',
            $labTest
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.reservations.export', [
                'period' => 'custom',
                'start_date' => '2026-07-01',
                'end_date' => '2026-07-10',
            ]));

        $content = $response->streamedContent();

        $this->assertStringContainsString('A001', $content);
        $this->assertStringNotContainsString('A002', $content);
    }

    public function test_custom_period_requires_dates(): void
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)
            ->get(route('admin.reservations.export', [
                'period' => 'custom',
            ]))
            ->assertSessionHasErrors([
                'start_date',
                'end_date',
            ]);
    }

    public function test_end_date_cannot_be_before_start_date(): void
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)
            ->get(route('admin.reservations.export', [
                'period' => 'custom',
                'start_date' => '2026-07-10',
                'end_date' => '2026-07-01',
            ]))
            ->assertSessionHasErrors('end_date');
    }

    public function test_patient_cannot_export_reservations(): void
    {
        $patient = $this->createUser('patient');

        $this->actingAs($patient)
            ->get(route('admin.reservations.export', [
                'period' => 'month',
            ]))
            ->assertForbidden();
    }

    public function test_guest_cannot_export_reservations(): void
    {
        $this->get(route('admin.reservations.export', [
            'period' => 'month',
        ]))
            ->assertRedirect(route('login'));
    }

    private function createUser(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }

    private function createLabTest(): LabTest
    {
        return LabTest::create([
            'name' => 'Hematologi Lengkap',
            'slug' => 'hematologi-lengkap',
            'description' => 'Layanan export.',
            'price' => 150000,
            'status' => 'active',
        ]);
    }

    private function createReservation(
        string $code,
        string $date,
        LabTest $labTest
    ): Reservation {
        $user = $this->createUser('patient');

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
            'status' => 'Menunggu',
            'notes' => 'Catatan export',
        ]);
    }
}