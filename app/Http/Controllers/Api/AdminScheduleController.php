<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\SwimClass;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    // GET /api/admin/classes/{classId}/schedules
    // Mengambil semua jadwal milik satu kelas tertentu
    public function index($classId)
    {
        // Gunakan try-catch untuk menangkap error backend dan melihatnya di log Laravel
        try {
            $schedules = ClassSchedule::with(['coachAssignments.coach'])
                ->where('class_id', $classId)
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();

            $formatted = $schedules->map(function ($s) {
                return [
                    'id'          => $s->id,
                    'class_id'    => $s->class_id,
                    // Paksa jadi integer
                    'day_of_week' => (int) $s->day_of_week,
                    'start_time'  => $s->start_time,
                    'end_time'    => $s->end_time,

                    // Handle jika coachAssignments null/kosong
                    'coaches'     => $s->coachAssignments ? $s->coachAssignments->map(function ($assign) {
                        // Handle jika relasi coach terputus (soft delete)
                        if (!$assign->coach) return null;

                        return [
                            'assignment_id' => $assign->id,
                            'coach_name'    => $assign->coach->full_name,
                            'is_primary'    => (bool) $assign->is_primary,
                        ];
                    })->filter()->values() : [],
                ];
            });

            return response()->json($formatted);
        } catch (\Exception $e) {
            // Jika error, return pesan error agar bisa dibaca di Flutter/Postman
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // POST /api/admin/schedules
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id'    => 'required|exists:swim_classes,id',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

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
