<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use Illuminate\Http\JsonResponse;

class LabTestApiController extends Controller
{
    public function index(): JsonResponse
    {
        $labTests = LabTest::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'price', 'status']);

        return response()->json([
            'success' => true,
            'message' => 'Data layanan pemeriksaan berhasil diambil.',
            'data' => $labTests,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $labTest = LabTest::where('slug', $slug)->first();

        if (! $labTest) {
            return response()->json([
                'success' => false,
                'message' => 'Data layanan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail layanan berhasil diambil.',
            'data' => $labTest,
        ]);
    }
}
