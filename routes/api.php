<?php

use App\Models\LabTest;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'MediLabs API berjalan.',
        'app' => 'MediLabs',
    ]);
});

Route::prefix('lab-tests')->group(function () {
    Route::get('/', function () {
        $labTests = LabTest::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $labTests,
        ]);
    });

    Route::get('/{slug}', function (string $slug) {
        $labTest = LabTest::query()
            ->where('slug', $slug)
            ->first();

        if (! $labTest) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $labTest,
        ]);
    });
});

Route::prefix('reservations')->group(function () {
    Route::get('/', function () {
        $reservations = Reservation::query()
            ->with(['patient', 'labTest'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservations,
        ]);
    });

    Route::get('/code/{code}', function (string $code) {
        $reservation = Reservation::query()
            ->with(['patient', 'labTest'])
            ->where('code', $code)
            ->first();

        if (! $reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $reservation,
        ]);
    });

    Route::patch('/{reservation}/status', function (Request $request, Reservation $reservation) {
        $validated = $request->validate([
            'status' => ['required', 'string'],
        ]);

        $reservation->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status reservasi berhasil diperbarui.',
            'data' => $reservation->load(['patient', 'labTest']),
        ]);
    });
});