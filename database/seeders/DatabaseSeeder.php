<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserStatusSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(SubRoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ClearancePurposeSeeder::class);
        $this->call(EmploymentTypeSeeder::class);
        $this->call(StatusSeeder::class);
    }
}
