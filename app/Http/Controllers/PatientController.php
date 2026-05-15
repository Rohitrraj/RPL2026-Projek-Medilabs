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
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('success', 'Silakan login terlebih dahulu sebelum mengisi data pasien.');
        }

        $patient = Patient::where('user_id', Auth::id())->latest()->first();

        // Jika data pasien sudah ada, halaman /data-pasien tidak perlu menampilkan ulang form kosong.
        // Form hanya ditampilkan untuk edit melalui /data-pasien?edit=1.
        if ($patient && ! $request->boolean('edit')) {
            return redirect()
                ->route('profile.show')
                ->with('success', 'Data pasien sudah tersimpan. Data dapat dilihat melalui halaman profil.');
        }

        return view('patients.create', compact('patient'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('success', 'Silakan login terlebih dahulu sebelum menyimpan data pasien.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'string', 'max:30'],
            'gender' => ['required', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'blood_type' => ['nullable', 'string', 'max:5'],
        ]);

        $patient = Patient::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        session(['current_patient_id' => $patient->id]);

        return redirect()
            ->route('reservations.create')
            ->with('success', 'Data pasien berhasil disimpan. Silakan lanjutkan reservasi.');
    }
}
