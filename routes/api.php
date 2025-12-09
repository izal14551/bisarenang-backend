<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PoolController;
use App\Http\Controllers\Api\SwimClassController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminMemberController;
use App\Http\Controllers\Api\AdminCoachController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/pools', [PoolController::class, 'index']);
Route::get('/classes', [SwimClassController::class, 'index']);
Route::get('/classes/{id}', [SwimClassController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    // Info akun yang login
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ---------- Member ----------
    // endpoint untuk MEMBER yang login (role = member)
    Route::get('/me/enrollments', [EnrollmentController::class, 'myEnrollments']);
    Route::get('/me/sessions',    [SessionController::class, 'mySessions']);
    Route::post('/sessions/{session}/member-check-in', [AttendanceController::class, 'memberCheckIn']);
    Route::post('/enrollments', [EnrollmentController::class, 'store']);

    // ---------- Coach ----------
    // nanti bisa tambah cek role 'coach' kalau mau lebih strict
    Route::get('/coaches/{coach}/sessions', [SessionController::class, 'coachSessions']);
    Route::post('/sessions/{session}/coach-check-in', [AttendanceController::class, 'coachCheckIn']);
});


Route::middleware(['auth:sanctum', 'admin'])
    ->prefix('admin')
    ->group(function () {
        // Member management
        Route::get('/members', [AdminMemberController::class, 'index']);
        Route::post('/members', [AdminMemberController::class, 'store']);
        Route::get('/members/{member}', [AdminMemberController::class, 'show']);
        Route::put('/members/{member}', [AdminMemberController::class, 'update']);
        Route::delete('/members/{member}', [AdminMemberController::class, 'destroy']);

        // Coach management
        Route::get('/coaches', [AdminCoachController::class, 'index']);
        Route::post('/coaches', [AdminCoachController::class, 'store']);
        Route::get('/coaches/{coach}', [AdminCoachController::class, 'show']);
        Route::put('/coaches/{coach}', [AdminCoachController::class, 'update']);
        Route::delete('/coaches/{coach}', [AdminCoachController::class, 'destroy']);
    });
