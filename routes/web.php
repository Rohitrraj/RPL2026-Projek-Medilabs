<?php

use App\Http\Controllers\MediLabsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MediLabsController::class, 'home'])->name('home');
Route::get('/daftar', [MediLabsController::class, 'register'])->name('register');
Route::get('/login', [MediLabsController::class, 'login'])->name('login');
Route::get('/data-pasien', [MediLabsController::class, 'patientForm'])->name('patients.create');
Route::get('/reservasi', [MediLabsController::class, 'reservationForm'])->name('reservations.create');
Route::get('/layanan', [MediLabsController::class, 'serviceDetail'])->name('services.show');
Route::get('/hasil-reservasi', [MediLabsController::class, 'reservationResult'])->name('reservations.result');
