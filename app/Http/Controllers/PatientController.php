<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function create(): View
    {
        $patient = Auth::check()
            ? Patient::where('user_id', Auth::id())->latest()->first()
            : null;

        return view('patients.create', compact('patient'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'string', 'max:30'],
            'gender' => ['required', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'blood_type' => ['nullable', 'string', 'max:5'],
        ]);

        $patient = Patient::where('nik', $validated['nik'])->first();

        if ($patient) {
            $patient->update(array_merge($validated, ['user_id' => Auth::id()]));
        } else {
            $patient = Patient::create(array_merge($validated, ['user_id' => Auth::id()]));
        }

        session(['current_patient_id' => $patient->id]);

        return redirect()
            ->route('reservations.create')
            ->with('success', 'Data pasien berhasil disimpan ke database. Silakan lanjutkan reservasi.');
    }
}
