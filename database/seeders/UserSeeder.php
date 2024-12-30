<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
            'name' => 'HR',
            'email' => 'hr@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'status' => 1,
        ],
        [
            'name' => 'official',
            'email' => 'official@gmail.com',
            'password' => bcrypt('password'), 
            'role_id' => 2,
            'status' => 1,
        ],
        [
            'name' => 'employee',
            'email' => 'employee@gmail.com',
            'password' => bcrypt('password'), 
            'role_id' => 3,
            'status' => 1,
        ]]
            
    );
    }
}
