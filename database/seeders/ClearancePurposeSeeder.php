<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ClearancePurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clearance_purpose')->insert([
            ['description' => 'Proportional Pay'],
            ['description' => 'Resignation'],
            ['description' => 'Retirement'],
            ['description' => 'Travel Abroad'],
            ['description' => 'Vacation/Sick Leave'],
            ['description' => 'Others'],
        ]);
    }
}
