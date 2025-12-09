<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\MemberCourseEnrollment;
use App\Models\SwimClass;
use App\Models\SwimMember;
use Illuminate\Http\Request;
use App\Http\Resources\MemberCourseEnrollmentResource;


class EnrollmentController extends Controller
{
    public function myEnrollments(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'member') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $member = $user->member;

        if (! $member) {
            return response()->json(['message' => 'Member profile not found'], 404);
        }

        $enrollments = MemberCourseEnrollment::with(['swimClass', 'schedule'])
            ->where('member_id', $member->id)
            ->where('status', 'active')
            ->get();

        return MemberCourseEnrollmentResource::collection($enrollments);
    }

    // GET /members/{member}/enrollments
    /* public function memberEnrollments($memberId)
    {
        $member = SwimMember::findOrFail($memberId);

        $enrollments = MemberCourseEnrollment::with(['swimClass', 'schedule'])
            ->where('member_id', $member->id)
            ->where('status', 'active')
            ->get();

        return MemberCourseEnrollmentResource::collection($enrollments);
    }
    */

    // POST /enrollments
    // body: member_id, class_id, schedule_id
    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id'   => 'required|exists:swim_members,id',
            'class_id'    => 'required|exists:swim_classes,id',
            'schedule_id' => 'nullable|exists:class_schedules,id',
        ]);

        $member = SwimMember::findOrFail($data['member_id']);
        $class  = SwimClass::findOrFail($data['class_id']);

        $scheduleId = $data['schedule_id'] ?? null;

        if ($class->schedule_type === 'per_week') {
            if (!$scheduleId) {
                return response()->json([
                    'message' => 'schedule_id is required for per_week classes',
                ], 422);
            }

            $schedule = ClassSchedule::where('id', $scheduleId)
                ->where('class_id', $class->id)
                ->firstOrFail();
        } else {
            $schedule = null;
        }

        // optional: cek duplikat enrollment
        $existing = MemberCourseEnrollment::where('member_id', $member->id)
            ->where('class_id', $class->id)
            ->when($scheduleId, fn($q) => $q->where('schedule_id', $scheduleId))
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Member already enrolled in this class/schedule',
            ], 409);
        }

        $enrollment = MemberCourseEnrollment::create([
            'member_id'        => $member->id,
            'class_id'         => $class->id,
            'schedule_id'      => $schedule?->id,
            'status'           => 'active',
            'enrollment_date'  => now()->toDateString(),
            'cancellation_date' => null,
        ]);

        $enrollment->load(['swimClass', 'schedule']);

        return (new MemberCourseEnrollmentResource($enrollment))
            ->response()
            ->setStatusCode(201);
    }
}
