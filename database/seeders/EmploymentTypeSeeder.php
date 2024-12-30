<?php

namespace Database\Seeders;

use App\Models\EmploymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employment_types = [
            'Teaching Clearance',
            'Non-Teaching Clearance'
        ];

        foreach ($employment_types as $employment_type)
        {
            EmploymentType::firstOrCreate([
                'description' => $employment_type
            ]);
        }
    }
}
