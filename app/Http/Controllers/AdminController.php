<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $reservations = Reservation::with(['patient', 'labTest'])->latest()->limit(5)->get();

        $stats = [
            'Total Reservasi' => Reservation::count(),
            'Reservasi Hari ini' => Reservation::whereDate('reservation_date', now()->toDateString())->count(),
            'Menunggu Konfirmasi' => Reservation::where('status', 'Menunggu')->count(),
            'Selesai' => Reservation::where('status', 'Selesai')->count(),
            'Dibatalkan' => Reservation::where('status', 'Dibatalkan')->count(),
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

        if (! $reservation) {
            $reservation = Reservation::with(['patient', 'labTest'])->latest()->first();
        }

        return view('admin.status', compact('reservation'));
    }

    public function manage(Request $request): View
    {
        $query = Reservation::with(['patient', 'labTest'])->latest();

        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        $reservations = $query->get();

        return view('admin.manage', compact('reservations'));
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Menunggu,Terjadwal,Diproses,Selesai,Dibatalkan'],
        ]);

        $reservation->update(['status' => $validated['status']]);

        return redirect()
            ->route('admin.reservations.manage')
            ->with('success', 'Status reservasi berhasil diperbarui.');
    }
}
