<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\ClassSessionInstance;
use App\Models\CoachScheduleAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
                    'status'       => $s->session_status, // scheduled, completed, cancelled
                    'attendance'   => $s->actual_attendance_count,
                ];
            });

        return response()->json($sessions);
    }

    // POST /api/admin/sessions/generate
    // Generate sesi otomatis untuk bulan tertentu
    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer|min:2024',
        ]);

        $month = $request->month;
        $year  = $request->year;

        // 1. Ambil semua jadwal aktif
        $schedules = ClassSchedule::where('is_active', true)->get();
        $count = 0;

        foreach ($schedules as $sch) {
            // 2. Cari tanggal-tanggal di bulan tersebut yang sesuai harinya
            // Carbon 0 = Minggu, 6 = Sabtu. Tapi di backend kita simpan integer sesuai input (misal 1=Senin)

            $startDate = Carbon::create($year, $month, 1);
            $endDate   = $startDate->copy()->endOfMonth();

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                // dayOfWeekIso: 1 (Senin) - 7 (Minggu)
                // dayOfWeek: 0 (Minggu) - 6 (Sabtu)
                // Sesuaikan dengan data di database. 
                // Asumsi di DB: 1=Senin ... 7=Minggu (ISO) atau 1=Senin ... 0=Minggu

                // Cek kesesuaian hari
                // Misal DB pakai: 1=Senin, 2=Selasa, ..., 7=Minggu
                if ($date->dayOfWeekIso == $sch->day_of_week) {

                    // 3. Cari Coach Utama untuk jadwal ini
                    $primaryAssignment = CoachScheduleAssignment::where('schedule_id', $sch->id)
                        ->where('is_primary', true)
                        ->first();

                    // 4. Buat Sesi jika belum ada
                    $session = ClassSessionInstance::firstOrCreate(
                        [
                            'schedule_id'  => $sch->id,
                            'session_date' => $date->format('Y-m-d'),
                        ],
                        [
                            'primary_coach_id' => $primaryAssignment?->coach_id,
                            'start_time'       => $sch->start_time,
                            'end_time'         => $sch->end_time,
                            'session_status'   => 'scheduled',
                        ]
                    );

                    if ($session->wasRecentlyCreated) {
                        $count++;
                    }
                }
            }
        }

        return response()->json([
            'message' => "Berhasil generate $count sesi baru untuk bulan $month/$year",
            'generated_count' => $count
        ]);
    }
}
