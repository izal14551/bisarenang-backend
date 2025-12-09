<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\ClassSessionInstance;
use App\Models\CoachScheduleAssignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;



class ClassSessionInstanceSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        $schedules = ClassSchedule::where('day_of_week', '!=', 'Flexible')->get();

        foreach ($schedules as $schedule) {
            // cari primary coach dari assignment
            $primaryAssignment = CoachScheduleAssignment::where('schedule_id', $schedule->id)
                ->where('is_primary', true)
                ->first();

            // hitung tanggal session terdekat berikutnya
            $sessionDate = $this->getNextDateForDayOfWeek($schedule->day_of_week, $today);

            ClassSessionInstance::create([
                'schedule_id'              => $schedule->id,
                'primary_coach_id'         => $primaryAssignment?->coach_id,
                'session_date'             => $sessionDate->toDateString(),
                'start_time'               => $schedule->start_time,   // planned
                'end_time'                 => $schedule->end_time,     // planned
                'session_status'           => 'scheduled',
                'actual_attendance_count'  => 0,
            ]);
        }
    }

    private function getNextDateForDayOfWeek(string $dayOfWeek, Carbon $from): Carbon
    {
        // Misal: from = hari ini, next('Saturday') = Sabtu berikutnya
        return $from->copy()->next($dayOfWeek);
    }
}
