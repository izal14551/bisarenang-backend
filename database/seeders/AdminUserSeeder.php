<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Cek kalau sudah ada admin agar tidak dobel
        if (!User::where('email', 'admin@bisarenang.test')->exists()) {
            User::create([
                'name'     => 'Super Admin',
                'email'    => 'admin@bisarenang.test',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]);
        }
    }
}
