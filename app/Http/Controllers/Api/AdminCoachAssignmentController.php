<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoachScheduleAssignment;
use Illuminate\Http\Request;

class AdminCoachAssignmentController extends Controller
{
    // POST /api/admin/coach-assignments
    public function store(Request $request)
    {
        $data = $request->validate([
            'schedule_id' => 'required|exists:class_schedules,id',
            'coach_id'    => 'required|exists:swim_coaches,id',
            'pool_assign_id' => 'required|exists:coach_pool_assignments,id',
            'is_primary'  => 'boolean',
        ]);

        // Cek apakah sudah ditugaskan sebelumnya
        $exists = CoachScheduleAssignment::where('schedule_id', $data['schedule_id'])
            ->where('coach_id', $data['coach_id'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Coach sudah ditugaskan di jadwal ini'], 422);
        }

        $assignment = CoachScheduleAssignment::create([
            'schedule_id' => $data['schedule_id'],
            'coach_id'    => $data['coach_id'],
            'pool_assign_id' => $data['pool_assign_id'],
            'is_primary'  => $data['is_primary'] ?? false,
            'effective_from' => now(),
        ]);

        return response()->json($assignment, 201);
    }

    // DELETE /api/admin/coach-assignments/{id}
    public function destroy($id)
    {
        $assignment = CoachScheduleAssignment::findOrFail($id);
        $assignment->delete();

        return response()->json(['message' => 'Penugasan berhasil dihapus']);
    }
}
