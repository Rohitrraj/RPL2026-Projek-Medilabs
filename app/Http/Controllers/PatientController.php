<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->ensureAuthenticated(
            'Silakan login terlebih dahulu sebelum mengisi data pasien.'
        )) {
            return $redirect;
        }

        $patient = $this->findCurrentPatient();

        if ($patient && ! $request->boolean('edit')) {
            return redirect()
                ->route('profile.show')
                ->with('success', 'Data pasien sudah tersimpan. Data dapat dilihat melalui halaman profil.');
        }

        return view('patients.create', compact('patient'));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->ensureAuthenticated(
            'Silakan login terlebih dahulu sebelum menyimpan data pasien.'
        )) {
            return $redirect;
        }

$validated = $request->validate(
    $this->patientValidationRules(),
    [
        'nik.unique' => 'NIK sudah terdaftar dan tidak boleh digunakan oleh akun lain.',
    ]
);

        $patient = Patient::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        session(['current_patient_id' => $patient->id]);

        return redirect()
            ->route('reservations.create')
            ->with('success', 'Data pasien berhasil disimpan. Silakan lanjutkan reservasi.');
    }

    private function ensureAuthenticated(string $message): ?RedirectResponse
    {
        if (Auth::check()) {
            return null;
        }

        return redirect()
            ->route('login')
            ->with('success', $message);
    }

    private function findCurrentPatient(): ?Patient
    {
        return Patient::where('user_id', Auth::id())
            ->latest()
            ->first();
    }

private function patientValidationRules(): array
{
    $currentPatientId = Patient::where('user_id', Auth::id())->value('id');

    return [
        'full_name' => ['required', 'string', 'max:100'],
        'nik' => ['required', 'string', 'max:30', 'unique:patients,nik,' . $currentPatientId],
        'gender' => ['required', 'string', 'max:20'],
        'birth_date' => ['nullable', 'date'],
        'phone' => ['required', 'string', 'max:20'],
        'address' => ['nullable', 'string'],
        'blood_type' => ['nullable', 'string', 'max:5'],
    ];
  }   
}