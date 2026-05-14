<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MediLabsController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'features' => $this->features(),
            'services' => $this->popularServices(),
        ]);
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function patientForm(): View
    {
        return view('patients.create');
    }

    public function reservationForm(): View
    {
        return view('reservations.create', [
            'patients' => ['Rohit Raj', 'Dewi Anggraini', 'Budi Santoso'],
            'tests' => ['Hematologi Lengkap', 'Gula Darah Puasa', 'Profil Lipid Lengkap', 'Asam Urat'],
            'hours' => ['08:00', '09:00', '10:00', '13:00', '15:00'],
        ]);
    }

    public function serviceDetail(): View
    {
        return view('services.show');
    }

    public function reservationResult(): View
    {
        return view('reservations.result', [
            'reservation' => [
                'Kode Reservasi' => 'A01000',
                'Nama Pasien' => 'Rohit Raj',
                'Jenis Tes' => 'Hematologi Lengkap',
                'Tanggal' => '15 Mei 2026',
                'Jam' => '09:00',
                'Nomor Antrian' => 'A-01',
                'Status' => 'Terjadwal',
            ],
        ]);
    }

    private function features(): array
    {
        return [
            ['icon' => 'user', 'title' => 'Pendaftaran Online', 'text' => 'Daftar pasien secara online dengan mudah dan cepat.'],
            ['icon' => 'calendar', 'title' => 'Pilih Jadwal', 'text' => 'Pilih tanggal dan jam pemeriksaan yang tersedia.'],
            ['icon' => 'flask', 'title' => 'Pilih Pemeriksaan', 'text' => 'Pilih jenis pemeriksaan lab sesuai kebutuhan.'],
            ['icon' => 'clipboard', 'title' => 'Cek Status', 'text' => 'Cek status reservasi secara online.'],
        ];
    }

    private function popularServices(): array
    {
        return [
            ['title' => 'Hematologi Lengkap', 'text' => 'Pemeriksaan darah lengkap untuk evaluasi kesehatan umum.'],
            ['title' => 'Gula Darah Puasa', 'text' => 'Pemeriksaan kadar gula setelah puasa sesuai instruksi.'],
            ['title' => 'Profil Lipid Lengkap', 'text' => 'Pemeriksaan kolesterol, LDL, HDL, dan trigliserida.'],
            ['title' => 'Asam Urat', 'text' => 'Pemeriksaan kadar asam urat dalam darah.'],
        ];
    }
}
