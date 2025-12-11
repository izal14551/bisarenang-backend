<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratorSessionRequest;
use App\Models\ClassSchedule;
use App\Models\ClassSessionInstance;
use App\Models\CoachScheduleAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\SessionGeneratorService;

class AdminSessionController extends Controller
{
    // GET /api/admin/sessions
    // Melihat daftar sesi yang sudah digenerate (filter per bulan)
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year  = $request->input('year', date('Y'));

        $sessions = ClassSessionInstance::with(['schedule.swimClass', 'primaryCoach'])
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->orderBy('session_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($s) {
                return [
                    'id'           => $s->id,
                    'class_name'   => $s->schedule->swimClass->name ?? '-',
                    'date'         => $s->session_date,
                    'time'         => substr($s->start_time, 0, 5) . ' - ' . substr($s->end_time, 0, 5),
                    'coach_name'   => $s->primaryCoach->full_name ?? 'Belum ada Coach',
                    'status'       => $s->session_status,
                    'attendance'   => $s->actual_attendance_count,
                ];
            });

        return response()->json($sessions);
    }

    // POST /api/admin/sessions/generate
    // Generate sesi otomatis untuk bulan tertentu
    public function generate(GeneratorSessionRequest $request, SessionGeneratorService $service)
    {
        $request->validated();

        $count = $service->generateForMonth($request->month, $request->year);

        return response()->json([
            'message' => "Berhasil generate $count sesi.",
            'data'    => ['count' => $count]
        ]);
    }
}
