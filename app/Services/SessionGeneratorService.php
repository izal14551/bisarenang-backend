<?php

namespace App\Services;

use App\Models\ClassSchedule;
use App\Models\ClassSessionInstance;
use App\Models\CoachScheduleAssignment;
use Carbon\Carbon;

class SessionGeneratorService
{
    public function generateForMonth($month, $year)
    {
        $schedules = ClassSchedule::where('is_active', true)->get();
        $count = 0;

        $startDate = Carbon::create($year, $month, 1);
        $endDate   = $startDate->copy()->endOfMonth();

        foreach ($schedules as $sch) {
            // Loop setiap hari di bulan tersebut
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                if ($date->dayOfWeekIso !== $sch->day_of_week) {
                    continue;
                }

                $this->createSessionIfNotExists($sch, $date);
                $count++;
            }
        }

        return $count;
    }

    private function createSessionIfNotExists($schedule, $date)
    {
        // Cari Coach Utama
        $primaryAssignment = CoachScheduleAssignment::where('schedule_id', $schedule->id)
            ->where('is_primary', true)
            ->first();

        return ClassSessionInstance::firstOrCreate(
            [
                'schedule_id'  => $schedule->id,
                'session_date' => $date->format('Y-m-d'),
            ],
            [
                'primary_coach_id' => $primaryAssignment?->coach_id,
                'start_time'       => $schedule->start_time,
                'end_time'         => $schedule->end_time,
                'session_status'   => 'scheduled',
            ]
        );
    }
}
