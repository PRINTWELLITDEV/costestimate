<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class U_MSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('u_m')->insert([
            ['UM' => 'SH', 'UMDesc' => 'Sheet', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['UM' => 'RL', 'UMDesc' => 'Roll', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
        ]);
    }
}
