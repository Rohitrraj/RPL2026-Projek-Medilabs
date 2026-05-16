<?php

use App\Http\Controllers\Api\LabTestApiController;
use App\Http\Controllers\Api\ReservationApiController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'MediLabs API berjalan.',
        'app' => 'MediLabs',
    ]);
});

Route::get('/lab-tests', [LabTestApiController::class, 'index']);
Route::get('/lab-tests/{slug}', [LabTestApiController::class, 'show']);

Route::get('/reservations', [ReservationApiController::class, 'index']);
Route::get('/reservations/code/{code}', [ReservationApiController::class, 'showByCode']);
Route::patch('/reservations/{reservation}/status', [ReservationApiController::class, 'updateStatus']);
