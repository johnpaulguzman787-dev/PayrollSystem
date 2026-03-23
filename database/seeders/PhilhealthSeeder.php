<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhilhealthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('philhealth_contributions')->insert([
        [
            'id' => 2,
            'contribution_rate' => 5.00,
            'employee_share' => 2.50,
            'employer_share' => 2.50,
            'min_salary' => 10000.00,
            'max_salary' => 100000.00,
            'status' => 'active',
            'created_at' => '2026-03-12 00:36:43',
            'updated_at' => '2026-03-12 00:36:43',
        ]
    ]);
}
}
