<?php

use App\Http\Controllers\MediLabsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MediLabsController::class, 'home'])->name('home');
Route::get('/daftar', [MediLabsController::class, 'register'])->name('register');
Route::get('/login', [MediLabsController::class, 'login'])->name('login');

Route::get('/data-pasien', [MediLabsController::class, 'patientForm'])->name('patients.create');
Route::get('/reservasi', [MediLabsController::class, 'reservationForm'])->name('reservations.create');
Route::get('/hasil-reservasi', [MediLabsController::class, 'reservationResult'])->name('reservations.result');

Route::get('/layanan', [MediLabsController::class, 'serviceIndex'])->name('services.index');
Route::get('/layanan/hematologi-lengkap', [MediLabsController::class, 'serviceDetail'])->name('services.show');

Route::get('/cek-status', [MediLabsController::class, 'reservationStatus'])->name('reservations.status');
Route::get('/riwayat-reservasi', [MediLabsController::class, 'reservationHistory'])->name('reservations.history');

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
| Route admin tetap disediakan sebagai persiapan tahap 100%.
*/
Route::get('/admin/dashboard', [MediLabsController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/admin/cek-status', [MediLabsController::class, 'adminReservationStatus'])->name('admin.status');
Route::get('/admin/kelola-reservasi', [MediLabsController::class, 'adminReservationManage'])->name('admin.manage');
