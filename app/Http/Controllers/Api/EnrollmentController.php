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

        $existing = MemberCourseEnrollment::withTrashed()
            ->where('class_id', $data['class_id'])
            ->where('member_id', $data['member_id'])
            ->where('schedule_id', $data['schedule_id'])
            ->first();

        if ($existing) {
            if ($existing->status === 'active' && !$existing->trashed()) {
                return response()->json([
                    'message' => 'Anda sudah terdaftar di kelas ini',
                ], 422);
            }

            $existing->restore();
            $existing->update([
                'status' => 'active',
                'enrollment_date' => now(),
                'cancellation_date' => null,
            ]);
            return response()->json($existing, 200);
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
