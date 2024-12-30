<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insertOrIgnore([
            ['description' => 'Pending'],
            ['description' => 'Verified'],
            ['description' => 'Approved'],
            ['description' => 'Pending Questionnaire'],
            ['description' => 'Completed'],
            ['description' => 'Disapproved'],
        ]);
    }
}
