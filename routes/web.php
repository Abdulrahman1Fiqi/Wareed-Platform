<?php

use App\Http\Controllers\Auth\DonorAuthController;
use App\Http\Controllers\Auth\HospitalAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hospital\DashboardController as HospitalDashboardController;
use App\Http\Controllers\Hospital\BloodRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HospitalController as AdminHospitalController;
use App\Http\Controllers\Admin\DonorController as AdminDonorController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;

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


Route::get('/donor/dashboard', fn() => view('donor.dashboard'))
    ->name('donor.dashboard')
    ->middleware(['donor', 'verified']);

Route::get('/hospital/dashboard', fn() => view('hospital.dashboard'))
    ->name('hospital.dashboard')
    ->middleware('hospital');



Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('hospitals')->name('hospitals.')->group(function () {
        Route::get('/', [AdminHospitalController::class, 'index'])->name('index');
        Route::get('/{hospital}', [AdminHospitalController::class, 'show'])->name('show');
        Route::post('/{hospital}/approve', [AdminHospitalController::class, 'approve'])->name('approve');
        Route::post('/{hospital}/reject', [AdminHospitalController::class, 'reject'])->name('reject');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminDonorController::class, 'index'])->name('index');
        Route::get('/{user}', [AdminDonorController::class, 'show'])->name('show');
        Route::post('/{user}/suspend', [AdminDonorController::class, 'suspend'])->name('suspend');
    });

    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [AdminRequestController::class, 'index'])->name('index');
        Route::get('/{bloodRequest}', [AdminRequestController::class, 'show'])->name('show');
    });

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});





Route::middleware('hospital')->prefix('hospital')->name('hospital.')->group(function () {
    Route::get('/dashboard', [HospitalDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [BloodRequestController::class, 'index'])->name('index');
        Route::get('/create', [BloodRequestController::class, 'create'])->name('create');
        Route::post('/', [BloodRequestController::class, 'store'])->name('store');
        Route::get('/{bloodRequest}', [BloodRequestController::class, 'show'])->name('show');
        Route::patch('/{bloodRequest}/status', [BloodRequestController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{bloodRequest}/responses/{donorResponse}/confirm', [BloodRequestController::class, 'confirmDonation'])->name('confirmDonation');
    });
});