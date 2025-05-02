<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
*/

// Default route - Redirect to login if not authenticated, else to dashboard
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : Inertia::render('Auth/Login');
})->name('home');

// ✅ Inertia authentication pages (Vue-based UI)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');
    Route::get('/register', fn () => Inertia::render('Auth/Register'))->name('register');
    Route::get('/otp', fn () => Inertia::render('Auth/OtpVerification'))->name('otp');
    Route::get('/proceed', fn () => Inertia::render('Auth/CompletSignupScreenOne'))->name('proceed');
    Route::get('/kyc', fn () => Inertia::render('Auth/KycVerification'))->name('kyc');
    Route::get('/create-passcode', fn () => Inertia::render('Auth/Passcode'))->name('create-passcode');
    Route::get('/pin', fn () => Inertia::render('Auth/TransactionPin'))->name('pin');
    Route::get('/welcome', fn () => Inertia::render('Auth/Welcome'))->name('welcome');
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/transfer', fn () => Inertia::render('Payments/Transfer'))->name('transfer');
    Route::get('/confirm', fn () => Inertia::render('Payments/Confirm'))->name('confirm');
    Route::get('/confirm-pin', fn () => Inertia::render('Payments/TransactionPin'))->name('confirm-pin');
    Route::get('/success', fn () => Inertia::render('Payments/TransferSuccess'))->name('success');

    // ✅ Handle form submission from Vue UI
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// ✅ Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Redirect authenticated users directly to the dashboard if they visit the login page
    Route::get('/login', fn() => redirect()->route('dashboard'))->name('login');
});
