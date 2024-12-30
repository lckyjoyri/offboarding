<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_status')->insert([
            ['description' => 'Active'],
            ['description' => 'Disabled'],
            ['description' => 'New'],
            ['description' => 'OnLeave'],
        ]);
    }
}
