<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SubRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_roles')->insert([
            ['description' => 'Supply Officer'],
            ['description' => 'Area Supervisor'],
            ['description' => 'Chairman of the Graduating Comm.'],
            ['description' => 'College Librarian'],
            ['description' => 'Head of Cafeteria'],
            ['description' => 'Campus Director'],
            ['description' => 'Cashier'],
            ['description' => 'Employee'],
        ]);
    }
}
