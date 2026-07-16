<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
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

    public function profile(): View
    {
        $user = Auth::user();
        $patient = $this->currentPatient($user?->id);
        $reservations = $this->recentReservationsForPatient($patient?->id);

        return view('profile', compact('user', 'patient', 'reservations'));
    }

    public function serviceIndex(): View
    {
        $services = LabTest::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('services.index', compact('services'));
    }

    public function serviceDetail(?string $slug = null): View
    {
    $service = LabTest::where('status', 'active')
        ->where('slug', $slug ?? 'hematologi-lengkap')
        ->firstOrFail();

    return view('services.show', compact('service'));
    }

    private function currentPatient(?int $userId): ?Patient
    {
        if (! $userId) {
            return null;
        }

        return Patient::where('user_id', $userId)
            ->latest()
            ->first();
    }

private function recentReservationsForPatient(?int $patientId)
{
    if (! $patientId) {
        return collect();
    }

    return Reservation::with(['patient', 'labTest'])
        ->where('patient_id', $patientId)
        ->latest()
        ->limit(5)
        ->get();
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
                'route' => Auth::check()
                    ? route('reservations.status')
                    : route('login', ['reason' => 'status']),
                'image' => 'assets/images/icon-cek-status.svg',
            ],
        ];
    }

    private function popularServices(): array
    {
        return LabTest::where('status', 'active')
            ->limit(4)
            ->get()
            ->map(function (LabTest $service) {
                return [
                    'title' => $service->name,
                    'text' => $service->description ?? 'Layanan pemeriksaan laboratorium MediLabs.',
                    'image' => config(
                        "service_images.{$service->slug}.card",
                        'assets/images/laypophematologi.jpeg'
                    ),
                    'route' => route('services.show', $service->slug),
                ];
            })
            ->toArray();
    }
}