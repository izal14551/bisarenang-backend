<?php

namespace Database\Seeders;

use App\Models\PoolLocation;
use Illuminate\Database\Seeder;

class PoolLocationSeeder extends Seeder
{
    public function run(): void
    {
        PoolLocation::create([
            'pool_name' => 'Kolam Renang Galaxy',
            'is_available' => true,
        ]);

        PoolLocation::create([
            'pool_name' => 'Kolam Renang Mentari',
            'is_available' => true,
        ]);

        PoolLocation::create([
            'pool_name' => 'Kolam Renang Surya',
            'is_available' => true,
        ]);
    }
}
