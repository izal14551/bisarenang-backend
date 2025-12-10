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
use App\Http\Controllers\Api\AdminPoolController;
use App\Http\Controllers\Api\AdminSwimClassController;
use App\Http\Controllers\Api\AdminScheduleController;
use App\Http\Controllers\Api\AdminEnrollmentController;
use App\Http\Controllers\Api\AdminCoachAssignmentController;
use App\Http\Controllers\Api\AdminSessionController;

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

        // Pool management
        Route::get('/pools', [AdminPoolController::class, 'index']);
        Route::post('/pools', [AdminPoolController::class, 'store']);
        Route::get('/pools/{pool}', [AdminPoolController::class, 'show']);
        Route::put('/pools/{pool}', [AdminPoolController::class, 'update']);
        Route::delete('/pools/{pool}', [AdminPoolController::class, 'destroy']);

        // Swim Class management
        Route::get('/classes', [AdminSwimClassController::class, 'index']);
        Route::post('/classes', [AdminSwimClassController::class, 'store']);
        Route::put('/classes/{swimClass}', [AdminSwimClassController::class, 'update']);
        Route::delete('/classes/{swimClass}', [AdminSwimClassController::class, 'destroy']);

        // Schedule Management
        Route::get('/classes/{classId}/schedules', [AdminScheduleController::class, 'index']);
        Route::post('/schedules', [AdminScheduleController::class, 'store']);
        Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy']);

        // Enrollment Management
        Route::get('/classes/{classId}/enrollments', [AdminEnrollmentController::class, 'index']);
        Route::post('/enrollments', [AdminEnrollmentController::class, 'store']);
        Route::delete('/enrollments/{id}', [AdminEnrollmentController::class, 'destroy']);

        // Coach Assignment
        Route::post('/coach-assignments', [AdminCoachAssignmentController::class, 'store']);
        Route::delete('/coach-assignments/{id}', [AdminCoachAssignmentController::class, 'destroy']);

        // Session Management
        Route::get('/sessions', [AdminSessionController::class, 'index']);
        Route::post('/sessions/generate', [AdminSessionController::class, 'generate']);
    });
