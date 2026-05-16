<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $reservations = Reservation::with(['patient', 'labTest'])
            ->latest()
            ->limit(6)
            ->get();

        $stats = [
            'Total Reservasi' => Reservation::count(),
            'Reservasi Hari Ini' => Reservation::whereDate('reservation_date', now()->toDateString())->count(),
            'Menunggu' => Reservation::where('status', 'Menunggu')->count(),
            'Terjadwal' => Reservation::where('status', 'Terjadwal')->count(),
            'Diproses' => Reservation::where('status', 'Diproses')->count(),
            'Selesai' => Reservation::where('status', 'Selesai')->count(),
            'Dibatalkan' => Reservation::where('status', 'Dibatalkan')->count(),
            'Pasien Terdaftar' => Patient::count(),
        ];

        return view('admin.dashboard', compact('stats', 'reservations'));
    }

    public function status(Request $request): View
    {
        $reservation = null;

        if ($request->filled('code')) {
            $reservation = Reservation::with(['patient', 'labTest'])
                ->where('code', $request->code)
                ->first();
        }

        return view('admin.status', compact('reservation'));
    }

    public function manage(Request $request): View
    {
        $query = Reservation::with(['patient', 'labTest'])->latest();

        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('reservation_date', $request->date);
        }

        if ($request->filled('lab_test_id')) {
            $query->where('lab_test_id', $request->lab_test_id);
        }

        $reservations = $query->paginate(10)->withQueryString();
        $labTests = LabTest::where('status', 'active')->orderBy('name')->get();
        $statuses = $this->statuses();

        return view('admin.manage', compact('reservations', 'labTests', 'statuses'));
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load(['patient.user', 'labTest']);
        $statuses = $this->statuses();

        return view('admin.show', compact('reservation', 'statuses'));
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', $this->statuses())],
        ]);

        $reservation->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()
            ->route('admin.reservations.manage')
            ->with('success', 'Data reservasi berhasil dihapus oleh admin.');
    }

    private function statuses(): array
    {
        return [
            'Menunggu',
            'Terjadwal',
            'Diproses',
            'Selesai',
            'Dibatalkan',
        ];
    }
}
