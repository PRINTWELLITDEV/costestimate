<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'site' => 'PI-SP',
                'userid' => 'sa',
                'name' => 'PI Tagapangasiwa',
                'password' => bcrypt('superadmin123'),
                'email' => 'printwellitdev@gmail.com',
                'department' => 'IT',
                'section' => 'Admin',
                'position' => 'Super Admin',
                'level' => 1,
                'status' => 1,
                'gender' => null,
                'profile_pic_url' => 'uploads/user-profile/super_admin.png',
                'create_date' => null,
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'remember_token' => null,
            ],
            [
                'site' => 'FP-SP',
                'userid' => 'PPR1181',
                'name' => 'Trick Torres',
                'password' => bcrypt('PPR1181'),
                'email' => 'patrick.torres@printwell.com.ph',
                'department' => 'IT',
                'section' => 'Dev',
                'position' => 'Developer',
                'level' => 1,
                'status' => 1,
                'gender' => 'Male',
                'profile_pic_url' => 'uploads/user-profile/PPR1181.png',
                'create_date' => '2025-09-09 09:33:32',
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'remember_token' => null,
            ],
            [
                'site' => 'PI-SP',
                'userid' => 'PPC1187',
                'name' => 'Aron Suarnaba',
                'password' => bcrypt('PPC1187'),
                'email' => 'aron.suarnaba@printwell.com.ph',
                'department' => 'IT',
                'section' => 'Dev',
                'position' => 'Developer',
                'level' => 1,
                'status' => 1,
                'gender' => 'Male',
                'profile_pic_url' => 'uploads/user-profile/PPC1187.png',
                'create_date' => '2025-09-09 09:33:32',
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'remember_token' => null,
            ],
            [
                'site' => 'PI-SP',
                'userid' => 'guest',
                'name' => 'Guest User',
                'password' => bcrypt('guest'),
                'email' => 'guest@email.com',
                'department' => 'General',
                'section' => 'Guest',
                'position' => 'Guest',
                'level' => 1,
                'status' => 0,
                'gender' => null,
                'profile_pic_url' => 'uploads/user-profile/68c94d415febc_guest.png',
                'create_date' => '2025-09-16 11:42:57.577',
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'remember_token' => null,
            ],
        ]);
    }
}
