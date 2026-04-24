<?php

use App\Http\Controllers\Auth\DonorAuthController;
use App\Http\Controllers\Auth\HospitalAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::middleware('guest')->group(function () {
    Route::get('/register/donor', [DonorAuthController::class, 'showRegister'])->name('register');
    Route::post('/register/donor', [DonorAuthController::class, 'register']);
    Route::get('/login', [DonorAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [DonorAuthController::class, 'login']);
});

Route::post('/logout', [DonorAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('guest:hospital')->group(function () {
    Route::get('/register/hospital', [HospitalAuthController::class, 'showRegister'])->name('hospital.register');
    Route::post('/register/hospital', [HospitalAuthController::class, 'register']);
    Route::get('/hospital/login', [HospitalAuthController::class, 'showLogin'])->name('hospital.login');
    Route::post('/hospital/login', [HospitalAuthController::class, 'login']);
});

Route::post('/hospital/logout', [HospitalAuthController::class, 'logout'])
    ->middleware('hospital')
    ->name('hospital.logout');

Route::get('/admin/login', fn() => view('auth.admin-login'))->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->middleware('admin')
    ->name('admin.logout');

Route::get('/donor/dashboard', fn() => view('donor.dashboard'))
    ->name('donor.dashboard')
    ->middleware(['donor', 'verified']);

Route::get('/hospital/dashboard', fn() => view('hospital.dashboard'))
    ->name('hospital.dashboard')
    ->middleware('hospital');

Route::get('/admin/dashboard', fn() => view('admin.dashboard'))
    ->name('admin.dashboard')
    ->middleware('admin');