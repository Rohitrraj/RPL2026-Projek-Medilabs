<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            'Hari Ini' => Reservation::whereDate('reservation_date', now()->toDateString())->count(),
            'Menunggu' => Reservation::where('status', 'Menunggu')->count(),
            'Terjadwal' => Reservation::where('status', 'Terjadwal')->count(),
            'Diproses' => Reservation::where('status', 'Diproses')->count(),
            'Selesai' => Reservation::where('status', 'Selesai')->count(),
            'Dibatalkan' => Reservation::where('status', 'Dibatalkan')->count(),
            'Pasien' => Patient::count(),
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
        $query = $this->applyReservationFilters($query, $request);

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

    public function servicesIndex(Request $request): View
    {
        $query = LabTest::query()->latest();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($innerQuery) use ($keyword) {
                $innerQuery->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('slug', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $services = $query->paginate(10)->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function servicesCreate(): View
    {
        return view('admin.services.form', [
            'service' => new LabTest(),
            'formMode' => 'create',
        ]);
    }

    public function servicesStore(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->serviceRules());

        LabTest::create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? $validated['name']),
            'description' => $validated['description'] ?? null,
            'benefit' => $validated['benefit'] ?? null,
            'preparation' => $validated['preparation'] ?? null,
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function servicesEdit(LabTest $labTest): View
    {
        return view('admin.services.form', [
            'service' => $labTest,
            'formMode' => 'edit',
        ]);
    }

    public function servicesUpdate(Request $request, LabTest $labTest): RedirectResponse
    {
        $validated = $request->validate($this->serviceRules($labTest->id));

        $labTest->update([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? $validated['name'], $labTest->id),
            'description' => $validated['description'] ?? null,
            'benefit' => $validated['benefit'] ?? null,
            'preparation' => $validated['preparation'] ?? null,
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function servicesToggleStatus(LabTest $labTest): RedirectResponse
    {
        $nextStatus = $labTest->status === 'active' ? 'inactive' : 'active';

        $labTest->update([
            'status' => $nextStatus,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status layanan berhasil diperbarui menjadi ' . $nextStatus . '.');
    }

    private function serviceRules(?int $ignoreId = null): array
    {
        $slugUniqueRule = 'unique:lab_tests,slug';

        if ($ignoreId) {
            $slugUniqueRule .= ',' . $ignoreId;
        }

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', $slugUniqueRule],
            'description' => ['nullable', 'string'],
            'benefit' => ['nullable', 'string'],
            'preparation' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 1;

        while (
            LabTest::query()
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function applyReservationFilters($query, Request $request)
    {
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

        return $query;
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