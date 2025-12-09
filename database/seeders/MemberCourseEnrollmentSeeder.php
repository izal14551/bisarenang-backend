<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\MemberCourseEnrollment;
use App\Models\SwimClass;
use App\Models\SwimMember;
use Illuminate\Database\Seeder;

class MemberCourseEnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $member1 = SwimMember::where('full_name', 'Fadel Afrizal')->first();
        $member2 = SwimMember::where('full_name', 'Adit Pratama')->first();
        $member3 = SwimMember::where('full_name', 'Sinta Lestari')->first();

        $kidsClass = SwimClass::where('name', 'Kelas Pemula Anak')->first();
        $adultClass = SwimClass::where('name', 'Kelas Dewasa Dasar')->first();

        $saturdayKidsSchedule = ClassSchedule::where('class_id', $kidsClass->id)
            ->where('day_of_week', 'Saturday')
            ->first();

        $sundayKidsSchedule = ClassSchedule::where('class_id', $kidsClass->id)
            ->where('day_of_week', 'Sunday')
            ->first();

        $saturdayAdultSchedule = ClassSchedule::where('class_id', $adultClass->id)
            ->where('day_of_week', 'Saturday')
            ->first();

        // Member 1 ikut kelas dewasa
        MemberCourseEnrollment::create([
            'member_id'        => $member1->id,
            'class_id'         => $adultClass->id,
            'schedule_id'      => $saturdayAdultSchedule->id,
            'status'           => 'active',
            'enrollment_date'  => now()->subDays(7)->toDateString(),
            'cancellation_date' => null,
        ]);

        // Member 2 & 3 ikut kelas anak (schedule Sabtu)
        MemberCourseEnrollment::create([
            'member_id'        => $member2->id,
            'class_id'         => $kidsClass->id,
            'schedule_id'      => $saturdayKidsSchedule->id,
            'status'           => 'active',
            'enrollment_date'  => now()->subDays(3)->toDateString(),
            'cancellation_date' => null,
        ]);

        MemberCourseEnrollment::create([
            'member_id'        => $member3->id,
            'class_id'         => $kidsClass->id,
            'schedule_id'      => $saturdayKidsSchedule->id,
            'status'           => 'active',
            'enrollment_date'  => now()->subDays(3)->toDateString(),
            'cancellation_date' => null,
        ]);
    }
}
