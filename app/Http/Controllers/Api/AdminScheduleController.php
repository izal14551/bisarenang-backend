<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\SwimClass;
use Illuminate\Http\Request;
use App\Http\Resources\AdminScheduleResource;
use App\Http\Requests\StoreScheduleRequest;

class AdminScheduleController extends Controller
{
    // GET /api/admin/classes/{classId}/schedules
    // Mengambil semua jadwal milik satu kelas tertentu
    public function index($classId)
    {
        $schedules = ClassSchedule::with(['coachAssignments.coach'])
            ->where('class_id', $classId)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->get();

        return AdminScheduleResource::collection($schedules);
    }

    // POST /api/admin/schedules
    public function store(StoreScheduleRequest $request)
    {
        $schedule = ClassSchedule::create($request->validated());
        return response()->json($schedule, 201);
    }

    public function destroy(ClassSchedule $schedule)
    {
        $schedule->update(['is_active' => false]);
        return response()->json(['message' => 'Schedule deactivated']);
    }
}
