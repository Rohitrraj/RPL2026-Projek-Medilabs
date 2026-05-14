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

    public function reservationResult(): View
    {
        return view('reservations.result', [
            'reservation' => $this->reservationDetail(),
        ]);
    }

    public function serviceIndex(): View
    {
        return view('services.index', [
            'services' => $this->allServices(),
        ]);
    }

    public function serviceDetail(): View
    {
        return view('services.show');
    }

    public function reservationStatus(): View
    {
        return view('reservations.status', [
            'reservation' => $this->reservationDetail(),
        ]);
    }

    public function reservationHistory(): View
    {
        return view('reservations.history', [
            'reservations' => [
                [
                    'Kode' => 'A01000',
                    'Jenis Tes' => 'Hematologi Lengkap',
                    'Tanggal' => '15 Mei 2026',
                    'Jam' => '09:00',
                    'Status' => 'Terjadwal',
                ],
                [
                    'Kode' => 'A01001',
                    'Jenis Tes' => 'Gula Darah Puasa',
                    'Tanggal' => '16 Mei 2026',
                    'Jam' => '08:00',
                    'Status' => 'Menunggu',
                ],
                [
                    'Kode' => 'A01002',
                    'Jenis Tes' => 'Profil Lipid Lengkap',
                    'Tanggal' => '17 Mei 2026',
                    'Jam' => '10:00',
                    'Status' => 'Selesai',
                ],
            ],
        ]);
    }

    public function adminDashboard(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'Total Reservasi' => 35,
                'Reservasi Hari ini' => 10,
                'Menunggu Konfirmasi' => 2,
                'Selesai' => 25,
                'Dibatalkan' => 9,
            ],
            'reservations' => $this->reservationRows(),
        ]);
    }

    public function adminReservationStatus(): View
    {
        return view('admin.status', [
            'reservation' => $this->reservationDetail(),
            'patient' => $this->patientDetail(),
        ]);
    }

    public function adminReservationManage(): View
    {
        return view('admin.manage', [
            'reservations' => $this->reservationRows(),
        ]);
    }

private function features(): array
{
    return [
        [
            'title' => 'Isi Data Pasien',
            'text' => 'Lengkapi data pasien sebelum melakukan reservasi pemeriksaan.',
            'route' => route('patients.create'),
            'image' => 'assets/images/icon-data-pasien.svg',
        ],
        [
            'title' => 'Pilih Layanan',
            'text' => 'Lihat dan pilih jenis pemeriksaan laboratorium yang dibutuhkan.',
            'route' => route('services.index'),
            'image' => 'assets/images/icon-pilih-layanan.svg',
        ],
        [
            'title' => 'Buat Reservasi',
            'text' => 'Tentukan jadwal, jam pemeriksaan, dan catatan keluhan pasien.',
            'route' => route('reservations.create'),
            'image' => 'assets/images/icon-buat-reservasi.svg',
        ],
        [
            'title' => 'Cek Status',
            'text' => 'Pantau status reservasi dan lihat detail jadwal pemeriksaan.',
            'route' => route('reservations.status'),
            'image' => 'assets/images/icon-cek-status.svg',
        ],
    ];
}

    private function popularServices(): array
    {
        return [
            [
                'title' => 'Hematologi Lengkap',
                'text' => 'Pemeriksaan darah lengkap untuk evaluasi kesehatan umum.',
                'image' => 'assets/images/laypophematologi.jpeg',
                'route' => route('services.show'),
            ],
            [
                'title' => 'Gula Darah Puasa',
                'text' => 'Pemeriksaan kadar gula setelah puasa sesuai instruksi.',
                'image' => 'assets/images/laypopguladarah.jpg',
                'route' => route('services.index'),
            ],
            [
                'title' => 'Profil Lipid Lengkap',
                'text' => 'Pemeriksaan kolesterol, LDL, HDL, dan trigliserida.',
                'image' => 'assets/images/laypopkolesterol.jpg',
                'route' => route('services.index'),
            ],
            [
                'title' => 'Asam Urat',
                'text' => 'Pemeriksaan kadar asam urat dalam darah.',
                'image' => 'assets/images/laypopasamurat.png',
                'route' => route('services.index'),
            ],
        ];
    }

    private function allServices(): array
    {
        return [
            [
                'title' => 'Hematologi Lengkap',
                'text' => 'Pemeriksaan darah lengkap untuk mengevaluasi hemoglobin, eritrosit, leukosit, trombosit, dan hematokrit.',
                'price' => 'Rp145.000',
                'image' => 'assets/images/laypophematologi.jpeg',
                'route' => route('services.show'),
            ],
            [
                'title' => 'Gula Darah Puasa',
                'text' => 'Pemeriksaan kadar gula darah setelah puasa untuk skrining dan pemantauan diabetes.',
                'price' => 'Rp55.000',
                'image' => 'assets/images/laypopguladarah.jpg',
                'route' => route('services.index'),
            ],
            [
                'title' => 'Profil Lipid Lengkap',
                'text' => 'Pemeriksaan kolesterol total, LDL, HDL, dan trigliserida untuk menilai risiko penyakit jantung.',
                'price' => 'Rp180.000',
                'image' => 'assets/images/laypopkolesterol.jpg',
                'route' => route('services.index'),
            ],
            [
                'title' => 'Asam Urat',
                'text' => 'Pemeriksaan kadar asam urat dalam darah untuk membantu deteksi risiko gout.',
                'price' => 'Rp45.000',
                'image' => 'assets/images/laypopasamurat.png',
                'route' => route('services.index'),
            ],
        ];
    }

    private function reservationDetail(): array
    {
        return [
            'Kode Reservasi' => 'A01000',
            'Nama Pasien' => 'Rohit Raj',
            'Jenis Tes' => 'Hematologi Lengkap',
            'Tanggal' => '15 Mei 2026',
            'Jam' => '09:00',
            'Nomor Antrian' => 'A-01',
            'Status' => 'Terjadwal',
        ];
    }

    private function patientDetail(): array
    {
        return [
            'NIK' => '3174xxxxxxxxxxxx',
            'Nama' => 'Rohit Raj',
            'No. Telepon' => '08xxxxxxxxxx',
            'Email' => 'rohit@example.com',
            'Alamat' => 'Jakarta',
        ];
    }

    private function reservationRows(): array
    {
        return [
            [
                'code' => 'A01000',
                'patient' => 'Rohit Raj',
                'test' => 'Hematologi Lengkap',
                'date' => '15 Mei 2026',
                'hour' => '09:00',
                'status' => 'Terjadwal',
            ],
        ];
    }
}
