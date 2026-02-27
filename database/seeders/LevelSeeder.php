<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('level')->truncate();
        DB::table('level')->insert([
            [
                'level' => 1,
                'role' => 'Super Admin',
                'description' => 'IT Administrator',
                'created_at' => now(),
            ],
            [
                'level' => 2,
                'role' => 'Admin',
                'description' => 'Manager, Supervisor',
                'created_at' => now(),
            ],
            [
                'level' => 3,
                'role' => 'User',
                'description' => 'Personnel, Staff',
                'created_at' => now(),
            ],
        ]);
    }
}
