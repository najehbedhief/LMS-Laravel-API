<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('/password/email', [ResetPasswordController::class, 'sendResetLink']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);
Route::get('/reset-password/{token}', function ($token) {
    return "Password reset token: $token";
})->name('password.reset');

Route::post('change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');

