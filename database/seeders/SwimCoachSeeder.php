<?php

namespace Database\Seeders;

use App\Models\SwimCoach;
use Illuminate\Database\Seeder;

class SwimCoachSeeder extends Seeder
{
    public function run(): void
    {
        SwimCoach::create([
            'full_name' => 'Coach Andi',
            'phone_number' => '081111111111',
            'is_active' => true,
        ]);

        SwimCoach::create([
            'full_name' => 'Coach Mira',
            'phone_number' => '082222222222',
            'is_active' => true,
        ]);

        SwimCoach::create([
            'full_name' => 'Coach Rudi',
            'phone_number' => '083333333333',
            'is_active' => true,
        ]);
    }
}
