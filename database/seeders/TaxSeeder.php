<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('tax_contributions')->insert([
        ['id'=>1,'income_from'=>0,'income_to'=>250000,'base_tax'=>0,'tax_rate'=>0.0000,'excess_over'=>0,'status'=>'active'],
        ['id'=>2,'income_from'=>250000.01,'income_to'=>400000,'base_tax'=>0,'tax_rate'=>0.1500,'excess_over'=>250000,'status'=>'active'],
        ['id'=>3,'income_from'=>400000.01,'income_to'=>800000,'base_tax'=>22500,'tax_rate'=>0.2000,'excess_over'=>400000,'status'=>'active'],
        ['id'=>4,'income_from'=>800000.01,'income_to'=>2000000,'base_tax'=>102500,'tax_rate'=>0.2500,'excess_over'=>800000,'status'=>'active'],
        ['id'=>5,'income_from'=>2000000.01,'income_to'=>8000000,'base_tax'=>402500,'tax_rate'=>0.3000,'excess_over'=>2000000,'status'=>'active'],
        ['id'=>6,'income_from'=>8000000.01,'income_to'=>null,'base_tax'=>2202500,'tax_rate'=>0.3500,'excess_over'=>8000000,'status'=>'active'],
    ]);
}
}
