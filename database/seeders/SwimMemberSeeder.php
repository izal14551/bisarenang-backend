<?php

namespace Database\Seeders;

use App\Models\SwimMember;
use Illuminate\Database\Seeder;

class SwimMemberSeeder extends Seeder
{
    public function run(): void
    {
        SwimMember::create([
            'full_name'     => 'Fadel Afrizal',
            'phone_number'  => '081111111111',
            'date_of_birth' => '2000-01-01',
            'is_active'     => true,
        ]);

        SwimMember::create([
            'full_name'     => 'Adit Pratama',
            'phone_number'  => '082222222222',
            'date_of_birth' => '2010-05-10',
            'is_active'     => true,
        ]);

        SwimMember::create([
            'full_name'     => 'Sinta Lestari',
            'phone_number'  => '083333333333',
            'date_of_birth' => '2015-03-20',
            'is_active'     => true,
        ]);
    }
}
