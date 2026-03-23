<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call([
            PagibigSeeder::class,
            PayrollItemsSettingsSeeder::class,
            PhilhealthSeeder::class,
            SssSeeder::class,
            TaxSeeder::class,
        ]);
    }
}
