<?php

namespace Database\Seeders;

use App\Models\SwimCoach;
use App\Models\PoolLocation;
use Illuminate\Database\Seeder;

class CoachPoolAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $pools = PoolLocation::all();
        $coaches = SwimCoach::all();

        // contoh: Coach 1 mengajar di Pool 1 & 2
        $coaches[0]->pools()->attach([
            $pools[0]->id => [
                'effective_from' => now()->toDateString(),
                'is_primary' => true,
            ],
            $pools[1]->id => [
                'effective_from' => now()->toDateString(),
                'is_primary' => false,
            ],
        ]);

        // contoh: Coach 2 mengajar hanya di Pool 2
        $coaches[1]->pools()->attach([
            $pools[1]->id => [
                'effective_from' => now()->toDateString(),
                'is_primary' => true,
            ],
        ]);

        // contoh: Coach 3 mengajar di Pool 3
        $coaches[2]->pools()->attach([
            $pools[2]->id => [
                'effective_from' => now()->toDateString(),
                'is_primary' => true,
            ],
        ]);
    }
}
