<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationApiController extends Controller
{
    public function index(): JsonResponse
    {
        $reservations = Reservation::with(['patient', 'labTest'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data reservasi berhasil diambil.',
            'data' => $reservations,
        ]);
    }

    public function showByCode(string $code): JsonResponse
    {
        $reservation = Reservation::with(['patient', 'labTest'])
            ->where('code', $code)
            ->first();

        if (! $reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail reservasi berhasil diambil.',
            'data' => $reservation,
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Menunggu,Terjadwal,Diproses,Selesai,Dibatalkan'],
        ]);

        $reservation->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status reservasi berhasil diperbarui melalui API.',
            'data' => $reservation->load(['patient', 'labTest']),
        ]);
    }
}
