<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassSessionInstance;
use App\Models\MemberCourseEnrollment;
use App\Models\SwimMember;
use App\Models\SwimCoach;
use Illuminate\Http\Request;
use App\Http\Resources\ClassSessionInstanceResource;

class SessionController extends Controller
{
    public function mySessions(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'member') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $member = $user->member;

        if (! $member) {
            return response()->json(['message' => 'Member profile not found'], 404);
        }

        $enrollmentIds = MemberCourseEnrollment::where('member_id', $member->id)
            ->where('status', 'active')
            ->pluck('id');

        $sessions = ClassSessionInstance::with(['schedule.swimClass', 'primaryCoach', 'memberSessionRecords'])
            ->whereHas('memberSessionRecords', function ($q) use ($enrollmentIds) {
                $q->whereIn('enrollment_id', $enrollmentIds);
            })
            ->orderBy('session_date')
            ->get();

        return ClassSessionInstanceResource::collection($sessions);
    }

    // GET /members/{member}/sessions
    /* public function memberSessions($memberId, Request $request)
    {
        $member = SwimMember::findOrFail($memberId);

        $enrollmentIds = MemberCourseEnrollment::where('member_id', $member->id)
            ->where('status', 'active')
            ->pluck('id');

        $sessions = ClassSessionInstance::with(['schedule.swimClass', 'primaryCoach', 'memberSessionRecords'])
            ->whereHas('memberSessionRecords', function ($q) use ($enrollmentIds) {
                $q->whereIn('enrollment_id', $enrollmentIds);
            })
            ->orderBy('session_date')
            ->get();

        return ClassSessionInstanceResource::collection($sessions);
    }
    */

    public function coachSessions($coachId, Request $request)
    {
        $coach = SwimCoach::findOrFail($coachId);

        $sessions = ClassSessionInstance::with(['schedule.swimClass', 'primaryCoach'])
            ->where('primary_coach_id', $coach->id)
            ->orWhereHas('coachAttendanceLogs', function ($q) use ($coach) {
                $q->where('coach_id', $coach->id);
            })
            ->orderBy('session_date')
            ->get();

        return ClassSessionInstanceResource::collection($sessions);
    }
}
