<?php

namespace Database\Seeders;

use App\Models\SwimClass;
use App\Models\PoolLocation;
use Illuminate\Database\Seeder;

class SwimClassSeeder extends Seeder
{
    public function run(): void
    {
        $pool1 = PoolLocation::where('pool_name', 'Kolam Renang Galaxy')->first();
        $pool2 = PoolLocation::where('pool_name', 'Kolam Renang Mentari')->first();

        // Kelas reguler (per minggu)
        SwimClass::create([
            'pool_id'       => $pool1->id,
            'name'          => 'Kelas Pemula Anak',
            'description'   => 'Untuk anak usia 6-12 tahun, belajar dasar berenang.',
            'schedule_type' => 'per_week',
            'max_capacity'  => 10,
            'is_active'     => true,
        ]);

        // Kelas reguler dewasa
        SwimClass::create([
            'pool_id'       => $pool1->id,
            'name'          => 'Kelas Dewasa Dasar',
            'description'   => 'Untuk dewasa yang belum bisa berenang.',
            'schedule_type' => 'per_week',
            'max_capacity'  => 12,
            'is_active'     => true,
        ]);

        // Kelas privat fleksibel
        SwimClass::create([
            'pool_id'       => $pool2->id,
            'name'          => 'Kelas Privat Dewasa',
            'description'   => 'Privat fleksibel 1-on-1.',
            'schedule_type' => 'flexible',
            'max_capacity'  => 1,
            'is_active'     => true,
        ]);
    }
}
