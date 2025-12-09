<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\CoachScheduleAssignment;
use App\Models\SwimCoach;
use Illuminate\Database\Seeder;

class CoachScheduleAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $coach1 = SwimCoach::where('full_name', 'Coach Andi')->first();
        $coach2 = SwimCoach::where('full_name', 'Coach Mira')->first();

        $schedules = ClassSchedule::all();

        // Coach Andi: pegang semua kelas anak & dewasa
        foreach ($schedules->where('day_of_week', '!=', 'Flexible') as $schedule) {
            CoachScheduleAssignment::create([
                'coach_id'       => $coach1->id,
                'schedule_id'    => $schedule->id,
                'is_primary'     => true,
                'effective_from' => now()->toDateString(),
                'effective_until' => null,
            ]);
        }

        // Coach Mira: backup untuk kelas anak hari Minggu
        $sundayKids = ClassSchedule::where('day_of_week', 'Sunday')->first();
        if ($sundayKids) {
            CoachScheduleAssignment::create([
                'coach_id'       => $coach2->id,
                'schedule_id'    => $sundayKids->id,
                'is_primary'     => false,
                'effective_from' => now()->toDateString(),
                'effective_until' => null,
            ]);
        }
    }
}
