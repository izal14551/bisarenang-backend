<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassSessionInstance;
use App\Models\MemberCourseEnrollment;
use App\Models\SwimMember;
use App\Models\SwimCoach;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    // GET /members/{member}/sessions
    public function memberSessions($memberId, Request $request)
    {
        $member = SwimMember::findOrFail($memberId);

        $enrollmentIds = MemberCourseEnrollment::where('member_id', $member->id)
            ->where('status', 'active')
            ->pluck('id');

        $sessions = ClassSessionInstance::with(['schedule.swimClass', 'memberSessionRecords'])
            ->whereHas('memberSessionRecords', function ($q) use ($enrollmentIds) {
                $q->whereIn('enrollment_id', $enrollmentIds);
            })
            ->orderBy('session_date')
            ->get();

        return response()->json($sessions);
    }

    // GET /coaches/{coach}/sessions
    public function coachSessions($coachId, Request $request)
    {
        $coach = SwimCoach::findOrFail($coachId);

        $sessions = ClassSessionInstance::with(['schedule.swimClass', 'coachAttendanceLogs'])
            ->where('primary_coach_id', $coach->id)
            ->orWhereHas('coachAttendanceLogs', function ($q) use ($coach) {
                $q->where('coach_id', $coach->id);
            })
            ->orderBy('session_date')
            ->get();

        return response()->json($sessions);
    }
}
