<?php

namespace Database\Seeders;

use App\Models\ClassSessionInstance;
use App\Models\CoachAttendanceLog;
use Illuminate\Database\Seeder;

class CoachAttendanceLogSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = ClassSessionInstance::all();

        foreach ($sessions as $session) {
            if ($session->primary_coach_id) {
                CoachAttendanceLog::create([
                    'session_id'    => $session->id,
                    'coach_id'      => $session->primary_coach_id,
                    'check_in_time' => $session->session_date . ' ' . $session->start_time,
                    'status'        => 'present',
                ]);
            }
        }
    }
}
