<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Registration and Login routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// OTP Verification route
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Protected routes for authenticated users
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        \Log::info('Authenticated user:', [$request->user()]);
        return $request->user();
    });
    Route::post('/update-user', [AuthController::class, 'updateUserInfo']);
    Route::post('/update-pin', [AuthController::class, 'updatePin']);
    Route::get('/balance', [TransactionController::class, 'balance']);
    Route::get('/verify-account/{user_id}', [TransactionController::class, 'verifyAccountId']);
    Route::post('/verify-pin', [TransactionController::class, 'verifyPin']);
    Route::post('/transfer', [TransactionController::class, 'InterTransfer']);

});
