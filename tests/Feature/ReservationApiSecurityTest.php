<?php

namespace Tests\Feature;

use App\Models\LabTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationApiSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_collection_api_is_not_publicly_available(): void
    {
        $this->getJson('/api/reservations')
            ->assertNotFound();
    }

    public function test_reservation_code_api_is_not_publicly_available(): void
    {
        $this->getJson('/api/reservations/code/A001')
            ->assertNotFound();
    }

    public function test_reservation_status_api_is_not_publicly_available(): void
    {
        $this->patchJson('/api/reservations/1/status', [
            'status' => 'Selesai',
        ])->assertNotFound();
    }

    public function test_health_api_remains_available(): void
    {
        $this->getJson('/api/health')
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'MediLabs API berjalan.',
                'app' => 'MediLabs',
            ]);
    }

    public function test_only_active_lab_tests_are_publicly_available(): void
    {
        $activeLabTest = LabTest::create([
            'name' => 'Hematologi Lengkap',
            'slug' => 'hematologi-lengkap',
            'description' => 'Pemeriksaan darah lengkap.',
            'benefit' => 'Menilai kondisi komponen darah.',
            'preparation' => 'Tidak memerlukan persiapan khusus.',
            'price' => 150000,
            'status' => 'active',
        ]);

        $inactiveLabTest = LabTest::create([
            'name' => 'Layanan Tidak Aktif',
            'slug' => 'layanan-tidak-aktif',
            'description' => 'Layanan untuk kebutuhan pengujian.',
            'benefit' => null,
            'preparation' => null,
            'price' => 100000,
            'status' => 'inactive',
        ]);

        $this->getJson('/api/lab-tests')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $activeLabTest->id,
                'slug' => 'hematologi-lengkap',
                'status' => 'active',
            ])
            ->assertJsonMissing([
                'id' => $inactiveLabTest->id,
                'slug' => 'layanan-tidak-aktif',
            ]);

        $this->getJson('/api/lab-tests/hematologi-lengkap')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $activeLabTest->id,
                'slug' => 'hematologi-lengkap',
            ]);

        $this->getJson('/api/lab-tests/layanan-tidak-aktif')
            ->assertNotFound()
            ->assertJson([
                'success' => false,
                'message' => 'Layanan tidak ditemukan.',
            ]);
    }
}