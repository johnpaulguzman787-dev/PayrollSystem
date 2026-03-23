<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagibigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('pagibig_contributions')->insert([
        [
            'id' => 1,
            'salary_cap' => 10000.00,
            'employee_rate_low' => 1.00,
            'employee_rate_high' => 2.00,
            'salary_threshold' => 1500.00,
            'status' => 'active',
            'created_at' => '2026-03-11 08:52:53',
            'updated_at' => '2026-03-11 08:52:53',
        ]
    ]);
}
}
