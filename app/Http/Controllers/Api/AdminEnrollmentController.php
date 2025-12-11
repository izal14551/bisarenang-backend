<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminEnrollmentRequest;
use App\Models\MemberCourseEnrollment;
use App\Models\SwimClass;
use App\Models\SwimMember;
use Illuminate\Http\Request;

class AdminEnrollmentController extends Controller
{
    // GET /api/admin/classes/{classId}/enrollments
    // Melihat daftar peserta di kelas tertentu
    public function index($classId)
    {
        $enrollments = MemberCourseEnrollment::with(['member', 'schedule'])
            ->where('class_id', $classId)
            ->where('status', 'active')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($e) {
                return [
                    'id'              => $e->id,
                    'member_id'       => $e->member_id,
                    'member_name'     => $e->member->full_name,
                    'member_phone'    => $e->member->phone_number,
                    'schedule_info'   => $e->schedule
                        ? ($e->schedule->day_of_week_name . ' ' . substr($e->schedule->start_time, 0, 5))
                        : 'Tanpa Jadwal',
                    'enrollment_date' => $e->enrollment_date,
                ];
            });

        return response()->json($enrollments);
    }

    // POST /api/admin/enrollments
    // Admin mendaftarkan member secara manual
    public function store(StoreAdminEnrollmentRequest $request)
    {
        $data = $request->validated();

        $scheduleId = $data['schedule_id'];

        $existingEnrollment = MemberCourseEnrollment::withTrashed()
            ->where('class_id', $data['class_id'])
            ->where('member_id', $data['member_id'])
            ->where('schedule_id', $scheduleId)
            ->first();

        if ($existingEnrollment) {
            if ($existingEnrollment->status === 'active' && !$existingEnrollment->trashed()) {
                return response()->json(['message' => 'Member sudah terdaftar di kelas ini'], 422);
            }

            $existingEnrollment->restore();
            $existingEnrollment->update([
                'status' => 'active',
                'enrollment_date' => now()->toDateString(),
                'cancellation_date' => null,
            ]);

            return response()->json($existingEnrollment, 200);
        }

        $enrollment = MemberCourseEnrollment::create([
            'class_id'        => $data['class_id'],
            'member_id'       => $data['member_id'],
            'schedule_id'     => $scheduleId,
            'status'          => 'active',
            'enrollment_date' => now()->toDateString(),
        ]);

        return response()->json($enrollment, 201);
    }

    // DELETE /api/admin/enrollments/{id}
    // Admin mengeluarkan member
    public function destroy($id)
    {
        $enrollment = MemberCourseEnrollment::findOrFail($id);
        $enrollment->update(['status' => 'cancelled', 'cancellation_date' => now()]);
        $enrollment->delete();

        return response()->json(['message' => 'Peserta berhasil dihapus dari kelas']);
    }
}
