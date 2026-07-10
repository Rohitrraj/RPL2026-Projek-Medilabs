<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediLabsController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return app(MediLabsController::class)->home();
})->name('home');

Route::controller(MediLabsController::class)->group(function () {
    Route::get('/layanan', 'serviceIndex')->name('services.index');
    Route::get('/layanan/{slug}', 'serviceDetail')->name('services.show');
});

Route::middleware('guest')->group(function () {
    Route::controller(MediLabsController::class)->group(function () {
        Route::get('/daftar', 'register')->name('register');
        Route::get('/login', 'login')->name('login');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('/daftar', 'register')->name('register.store');
        Route::post('/login', 'login')->name('login.store');
    });
});


Route::get('/cek-status', [ReservationController::class, 'status'])
    ->name('reservations.status');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [MediLabsController::class, 'profile'])
        ->name('profile.show');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::controller(PatientController::class)->group(function () {
        Route::get('/data-pasien', 'create')->name('patients.create');
        Route::post('/data-pasien', 'store')->name('patients.store');
    });

    Route::controller(ReservationController::class)->group(function () {
        Route::get('/reservasi', 'create')->name('reservations.create');
        Route::post('/reservasi', 'store')->name('reservations.store');

        Route::get('/hasil-reservasi/{reservation}', 'result')
            ->name('reservations.result');

        Route::get('/riwayat-reservasi', 'history')
            ->name('reservations.history');

        Route::delete('/riwayat-reservasi/{reservation}', 'destroy')
            ->name('reservations.destroy');
    });
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->controller(AdminController::class)
    ->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/cek-status', 'status')->name('reservations.status');

        Route::get('/kelola-reservasi', 'manage')->name('reservations.manage');
        Route::get('/kelola-reservasi/{reservation}', 'show')->name('reservations.show');
        Route::patch('/kelola-reservasi/{reservation}/status', 'updateStatus')->name('reservations.update-status');
        Route::delete('/kelola-reservasi/{reservation}', 'destroy')->name('reservations.destroy');

        // Admin services
        Route::get('/layanan', 'servicesIndex')->name('services.index');
        Route::get('/layanan/tambah', 'servicesCreate')->name('services.create');
        Route::post('/layanan', 'servicesStore')->name('services.store');
        Route::get('/layanan/{labTest}/edit', 'servicesEdit')->name('services.edit');
        Route::put('/layanan/{labTest}', 'servicesUpdate')->name('services.update');
        Route::patch('/layanan/{labTest}/toggle-status', 'servicesToggleStatus')->name('services.toggle-status');
    });