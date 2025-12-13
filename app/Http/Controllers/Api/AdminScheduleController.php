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
    private $dayOrder = [
        'Senin' => 1,
        'Selasa' => 2,
        'Rabu' => 3,
        'Kamis' => 4,
        'Jumat' => 5,
        'Sabtu' => 6,
        'Minggu' => 7
    ];

    // GET /api/admin/classes/{classId}/schedules
    // Mengambil semua jadwal milik satu kelas tertentu
    public function index($classId)
    {
        $schedules = ClassSchedule::with(['coachAssignments.coach'])
            ->where('class_id', $classId)
            ->where('is_active', true)
            ->get();

        // Urutkan berdasarkan hari dalam minggu dan jam mulai
        $schedules = $schedules->sortBy(function ($schedule) {
            return $this->dayOrder[$schedule->day_of_week] ?? 99;
        })->sortBy('start_time');

        return AdminScheduleResource::collection($schedules);
    }

    // POST /api/admin/schedules
    public function store(StoreScheduleRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = true;

        $schedule = ClassSchedule::create($data);
        return response()->json($schedule, 201);
    }

    public function destroy(ClassSchedule $schedule)
    {
        $schedule->update(['is_active' => false]);
        return response()->json(['message' => 'Schedule deactivated']);
    }
}
