
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PoolController;
use App\Http\Controllers\Api\SwimClassController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\AttendanceController;

// Public
Route::get('/pools', [PoolController::class, 'index']);
Route::get('/classes', [SwimClassController::class, 'index']);
Route::get('/classes/{id}', [SwimClassController::class, 'show']);

// Member
Route::get('/members/{member}/enrollments', [EnrollmentController::class, 'memberEnrollments']);
Route::post('/enrollments', [EnrollmentController::class, 'store']);
Route::get('/members/{member}/sessions', [SessionController::class, 'memberSessions']);
Route::post('/sessions/{session}/member-check-in', [AttendanceController::class, 'memberCheckIn']);

// Coach
Route::get('/coaches/{coach}/sessions', [SessionController::class, 'coachSessions']);
Route::post('/sessions/{session}/coach-check-in', [AttendanceController::class, 'coachCheckIn']);
?>
