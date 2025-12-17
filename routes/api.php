<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ResetPasswordController;

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


// Course
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('courses', CourseController::class);
    Route::post('course/{id}', [CourseController::class,'updateCourse']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::apiResource('lessons', LessonController::class);
    
    Route::post('enroll/course/{id}', [CourseController::class , 'enrollToCourse']);
    
});
