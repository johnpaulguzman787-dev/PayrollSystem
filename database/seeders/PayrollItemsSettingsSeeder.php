<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayrollItemsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    

public function run()
{
    DB::table('payroll_items_settings')->insert([
        [
            'id' => 1,
            'code' => 'LATE',
            'name' => 'Late',
            'category' => 'late',
            'type' => 'deduction',
            'basis' => 'per_minute',
            'multiplier' => 10.00,
            'is_paid' => 0,
            'is_active' => 1,
            'created_at' => '2026-03-07 18:44:15',
            'updated_at' => '2026-03-15 21:42:53',
        ],
        [
            'id' => 2,
            'code' => 'UNDERTIME',
            'name' => 'Undertime',
            'category' => 'undertime',
            'type' => 'deduction',
            'basis' => 'per_minute',
            'multiplier' => 1.00,
            'is_paid' => 0,
            'is_active' => 1,
            'created_at' => '2026-03-07 18:44:15',
            'updated_at' => '2026-03-07 18:44:15',
        ],
        [
            'id' => 3,
            'code' => 'OVERTIME',
            'name' => 'Overtime',
            'category' => 'overtime',
            'type' => 'earning',
            'basis' => 'per_hour',
            'multiplier' => 1.00,
            'is_paid' => 1,
            'is_active' => 1,
            'created_at' => '2026-03-07 18:44:15',
            'updated_at' => '2026-03-07 18:44:15',
        ],
        [
            'id' => 4,
            'code' => 'HOLIDAY',
            'name' => 'Holiday',
            'category' => 'holiday',
            'type' => 'earning',
            'basis' => 'per_day',
            'multiplier' => 1.00,
            'is_paid' => 1,
            'is_active' => 1,
            'created_at' => '2026-03-07 18:44:15',
            'updated_at' => '2026-03-07 18:44:15',
        ],
        [
            'id' => 5,
            'code' => 'LEAVE',
            'name' => 'Leave',
            'category' => 'leave',
            'type' => 'earning',
            'basis' => 'per_day',
            'multiplier' => 50.00,
            'is_paid' => 1,
            'is_active' => 1,
            'created_at' => '2026-03-07 18:44:15',
            'updated_at' => '2026-03-09 19:44:28',
        ],
        [
            'id' => 6,
            'code' => 'ABSENT',
            'name' => 'Absent',
            'category' => 'absent',
            'type' => 'deduction',
            'basis' => 'per_day',
            'multiplier' => 1.00,
            'is_paid' => 0,
            'is_active' => 1,
            'created_at' => '2026-03-07 18:44:15',
            'updated_at' => '2026-03-09 19:33:13',
        ],
    ]);
}
}
