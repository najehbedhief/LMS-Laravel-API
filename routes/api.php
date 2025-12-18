<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\LessonProgressController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('/password/email', [ResetPasswordController::class, 'sendResetLink']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);
Route::get('/reset-password/{token}', function ($token) {
    return "Password reset token: $token";
})->name('password.reset');

// Course
Route::middleware('auth:sanctum')->group(function () {
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::middleware('role:Instructor')->group(function () {
        Route::apiResource('courses', CourseController::class)->except(['index', 'show']);
        Route::post('course/{id}', [CourseController::class, 'updateCourse']);
    });

    Route::apiResource('lessons', LessonController::class);
    Route::post('/lessons/{lesson}/progress', [LessonProgressController::class, 'lessonProgress']);


    Route::post('enroll/course/{id}', [CourseController::class, 'enrollToCourse']);

    Route::middleware('role:Student')->group(function () {
        Route::post('/request-role', [RoleController::class, 'requestRole']);
    });

    Route::middleware('role:Admin')->group(function () {
        Route::post('/request-role/{id}/approve', [AdminController::class, 'approveRequest']);
        Route::post('/request-role/{id}/reject', [AdminController::class, 'rejectRequest']);
    });

});
