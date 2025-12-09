<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PoolLocationSeeder::class,
            SwimCoachSeeder::class,
            CoachPoolAssignmentSeeder::class,

            SwimMemberSeeder::class,
            SwimClassSeeder::class,
            ClassScheduleSeeder::class,

            CoachScheduleAssignmentSeeder::class,
            MemberCourseEnrollmentSeeder::class,
            ClassSessionInstanceSeeder::class,
            MemberSessionRecordSeeder::class,
            CoachAttendanceLogSeeder::class,
        ]);
    }
}
