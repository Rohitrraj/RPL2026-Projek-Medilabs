<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediLabsController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MediLabsController::class, 'home'])->name('home');

Route::get('/daftar', [MediLabsController::class, 'register'])->name('register');
Route::post('/daftar', [AuthController::class, 'register'])->name('register.store');

Route::get('/login', [MediLabsController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

Route::get('/data-pasien', [PatientController::class, 'create'])->name('patients.create');
Route::post('/data-pasien', [PatientController::class, 'store'])->name('patients.store');

Route::get('/reservasi', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/hasil-reservasi/{reservation}', [ReservationController::class, 'result'])->name('reservations.result');
Route::get('/cek-status', [ReservationController::class, 'status'])->name('reservations.status');
Route::get('/riwayat-reservasi', [ReservationController::class, 'history'])->name('reservations.history');
Route::delete('/riwayat-reservasi/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

Route::get('/layanan', [MediLabsController::class, 'serviceIndex'])->name('services.index');
Route::get('/layanan/{slug}', [MediLabsController::class, 'serviceDetail'])->name('services.show');

/*
|--------------------------------------------------------------------------
| Admin routes - backend sesi 3 75%
|--------------------------------------------------------------------------
| Route admin dilindungi middleware auth + admin agar hanya user role admin
| yang dapat membuka dashboard dan mengelola reservasi.
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/cek-status', [AdminController::class, 'status'])->name('reservations.status');
        Route::get('/kelola-reservasi', [AdminController::class, 'manage'])->name('reservations.manage');
        Route::get('/kelola-reservasi/{reservation}', [AdminController::class, 'show'])->name('reservations.show');
        Route::patch('/kelola-reservasi/{reservation}/status', [AdminController::class, 'updateStatus'])->name('reservations.update-status');
        Route::delete('/kelola-reservasi/{reservation}', [AdminController::class, 'destroy'])->name('reservations.destroy');
    });
