<?php

namespace Database\Seeders;

use App\Models\ClassSessionInstance;
use App\Models\MemberCourseEnrollment;
use App\Models\MemberSessionRecord;
use Illuminate\Database\Seeder;

class MemberSessionRecordSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = ClassSessionInstance::all();

        foreach ($sessions as $session) {
            // ambil enrollment yang schedule_id-nya sama dengan schedule session tersebut
            $enrollments = MemberCourseEnrollment::where('schedule_id', $session->schedule_id)->get();

            foreach ($enrollments as $enrollment) {
                MemberSessionRecord::create([
                    'session_id'    => $session->id,
                    'enrollment_id' => $enrollment->id,
                    'check_in_time' => null,
                    'status'        => 'expected',
                ]);
            }
        }
    }
}
