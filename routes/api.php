<?php

use App\Models\LabTest;
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
            ->where('status', 'active')
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