<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassSessionInstance;
use App\Models\CoachAttendanceLog;
use App\Models\MemberCourseEnrollment;
use App\Models\MemberSessionRecord;
use App\Models\SwimCoach;
use App\Models\SwimMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // POST /sessions/{session}/member-check-in
    // body: member_id
    public function memberCheckIn($sessionId, Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'member') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $member = $user->member;

        if (! $member) {
            return response()->json(['message' => 'Member profile not found'], 404);
        }

        $session = ClassSessionInstance::findOrFail($sessionId);

        // cari enrollment member terkait schedule session ini
        $enrollment = MemberCourseEnrollment::where('member_id', $member->id)
            ->where('schedule_id', $session->schedule_id)
            ->where('status', 'active')
            ->first();

        if (! $enrollment) {
            return response()->json([
                'message' => 'Member is not enrolled for this session schedule',
            ], 404);
        }

        return DB::transaction(function () use ($session, $enrollment) {
            $record = MemberSessionRecord::firstOrCreate(
                [
                    'session_id'    => $session->id,
                    'enrollment_id' => $enrollment->id,
                ],
                [
                    'status'        => 'expected',
                ]
            );

            $record->check_in_time = now();
            $record->status = 'attended';
            $record->save();

            $session->actual_attendance_count = MemberSessionRecord::where('session_id', $session->id)
                ->where('status', 'attended')
                ->count();
            $session->save();

            return response()->json([
                'message' => 'Member checked in',
                'record'  => $record,
                'session' => $session,
            ]);
        });
    }


    // POST /sessions/{session}/coach-check-in
    // body: coach_id
    public function coachCheckIn($sessionId, Request $request)
    {
        $data = $request->validate([
            'coach_id' => 'required|exists:swim_coaches,id',
        ]);

        $session = ClassSessionInstance::findOrFail($sessionId);
        $coach   = SwimCoach::findOrFail($data['coach_id']);

        $log = CoachAttendanceLog::create([
            'session_id'    => $session->id,
            'coach_id'      => $coach->id,
            'check_in_time' => now(),
            'status'        => 'present',
        ]);

        return response()->json([
            'message' => 'Coach checked in',
            'log'     => $log,
        ], 201);
    }
}
