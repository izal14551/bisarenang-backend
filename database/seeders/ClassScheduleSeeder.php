<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\SwimClass;
use Illuminate\Database\Seeder;

class ClassScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $kidsClass = SwimClass::where('name', 'Kelas Pemula Anak')->first();
        $adultClass = SwimClass::where('name', 'Kelas Dewasa Dasar')->first();
        $privateClass = SwimClass::where('name', 'Kelas Privat Dewasa')->first();

        // Kelas anak: Sabtu & Minggu
        ClassSchedule::create([
            'class_id'   => $kidsClass->id,
            'day_of_week' => 'Saturday',
            'start_time' => '08:00:00',
            'end_time'   => '09:00:00',
            'is_active'  => true,
        ]);

        ClassSchedule::create([
            'class_id'   => $kidsClass->id,
            'day_of_week' => 'Sunday',
            'start_time' => '08:00:00',
            'end_time'   => '09:00:00',
            'is_active'  => true,
        ]);

        // Kelas dewasa: Sabtu
        ClassSchedule::create([
            'class_id'   => $adultClass->id,
            'day_of_week' => 'Saturday',
            'start_time' => '09:00:00',
            'end_time'   => '10:00:00',
            'is_active'  => true,
        ]);

        // Kelas privat fleksibel: kita kasih 1 slot default
        ClassSchedule::create([
            'class_id'   => $privateClass->id,
            'day_of_week' => 'Flexible', // kamu boleh pakai 'Flexible' atau null
            'start_time' => '00:00:00',
            'end_time'   => '00:00:00',
            'is_active'  => true,
        ]);
    }
}
