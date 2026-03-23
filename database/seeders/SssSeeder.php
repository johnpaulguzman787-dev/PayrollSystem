<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SSSContributionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contributions = [
            ['id' => 5, 'salary_from' => 0.00, 'salary_to' => 5249.99, 'monthly_salary_credit' => 5000.00, 'employee_share' => 250.00, 'employer_share' => 0.00, 'total' => 250.00, 'status' => 'active'],
            ['id' => 6, 'salary_from' => 5250.00, 'salary_to' => 5749.99, 'monthly_salary_credit' => 5500.00, 'employee_share' => 275.00, 'employer_share' => 0.00, 'total' => 275.00, 'status' => 'active'],
            ['id' => 7, 'salary_from' => 5750.00, 'salary_to' => 6249.99, 'monthly_salary_credit' => 6000.00, 'employee_share' => 300.00, 'employer_share' => 0.00, 'total' => 300.00, 'status' => 'active'],
            ['id' => 8, 'salary_from' => 6250.00, 'salary_to' => 6749.99, 'monthly_salary_credit' => 6500.00, 'employee_share' => 325.00, 'employer_share' => 0.00, 'total' => 325.00, 'status' => 'active'],
            ['id' => 9, 'salary_from' => 6750.00, 'salary_to' => 7249.99, 'monthly_salary_credit' => 7000.00, 'employee_share' => 350.00, 'employer_share' => 0.00, 'total' => 350.00, 'status' => 'active'],
            ['id' => 10, 'salary_from' => 7250.00, 'salary_to' => 7749.99, 'monthly_salary_credit' => 7500.00, 'employee_share' => 375.00, 'employer_share' => 0.00, 'total' => 375.00, 'status' => 'active'],
            ['id' => 11, 'salary_from' => 7750.00, 'salary_to' => 8249.99, 'monthly_salary_credit' => 8000.00, 'employee_share' => 400.00, 'employer_share' => 0.00, 'total' => 400.00, 'status' => 'active'],
            ['id' => 12, 'salary_from' => 8250.00, 'salary_to' => 8749.99, 'monthly_salary_credit' => 8500.00, 'employee_share' => 425.00, 'employer_share' => 0.00, 'total' => 425.00, 'status' => 'active'],
            ['id' => 13, 'salary_from' => 8750.00, 'salary_to' => 9249.99, 'monthly_salary_credit' => 9000.00, 'employee_share' => 450.00, 'employer_share' => 0.00, 'total' => 450.00, 'status' => 'active'],
            ['id' => 14, 'salary_from' => 9250.00, 'salary_to' => 9749.99, 'monthly_salary_credit' => 9500.00, 'employee_share' => 475.00, 'employer_share' => 0.00, 'total' => 475.00, 'status' => 'active'],
            ['id' => 15, 'salary_from' => 9750.00, 'salary_to' => 10249.99, 'monthly_salary_credit' => 10000.00, 'employee_share' => 500.00, 'employer_share' => 0.00, 'total' => 500.00, 'status' => 'active'],
            ['id' => 16, 'salary_from' => 10250.00, 'salary_to' => 10749.99, 'monthly_salary_credit' => 10500.00, 'employee_share' => 525.00, 'employer_share' => 0.00, 'total' => 525.00, 'status' => 'active'],
            ['id' => 17, 'salary_from' => 10750.00, 'salary_to' => 11249.99, 'monthly_salary_credit' => 11000.00, 'employee_share' => 550.00, 'employer_share' => 0.00, 'total' => 550.00, 'status' => 'active'],
            ['id' => 18, 'salary_from' => 11250.00, 'salary_to' => 11749.99, 'monthly_salary_credit' => 11500.00, 'employee_share' => 575.00, 'employer_share' => 0.00, 'total' => 575.00, 'status' => 'active'],
            ['id' => 19, 'salary_from' => 11750.00, 'salary_to' => 12249.99, 'monthly_salary_credit' => 12000.00, 'employee_share' => 600.00, 'employer_share' => 0.00, 'total' => 600.00, 'status' => 'active'],
            ['id' => 20, 'salary_from' => 12250.00, 'salary_to' => 12749.99, 'monthly_salary_credit' => 12500.00, 'employee_share' => 625.00, 'employer_share' => 0.00, 'total' => 625.00, 'status' => 'active'],
            ['id' => 21, 'salary_from' => 12750.00, 'salary_to' => 13249.99, 'monthly_salary_credit' => 13000.00, 'employee_share' => 650.00, 'employer_share' => 0.00, 'total' => 650.00, 'status' => 'active'],
            ['id' => 22, 'salary_from' => 13250.00, 'salary_to' => 13749.99, 'monthly_salary_credit' => 13500.00, 'employee_share' => 675.00, 'employer_share' => 0.00, 'total' => 675.00, 'status' => 'active'],
            ['id' => 23, 'salary_from' => 13750.00, 'salary_to' => 14249.99, 'monthly_salary_credit' => 14000.00, 'employee_share' => 700.00, 'employer_share' => 0.00, 'total' => 700.00, 'status' => 'active'],
            ['id' => 24, 'salary_from' => 14250.00, 'salary_to' => 14749.99, 'monthly_salary_credit' => 14500.00, 'employee_share' => 725.00, 'employer_share' => 0.00, 'total' => 725.00, 'status' => 'active'],
            ['id' => 25, 'salary_from' => 14750.00, 'salary_to' => 15249.99, 'monthly_salary_credit' => 15000.00, 'employee_share' => 750.00, 'employer_share' => 0.00, 'total' => 750.00, 'status' => 'active'],
            ['id' => 26, 'salary_from' => 15250.00, 'salary_to' => 15749.99, 'monthly_salary_credit' => 15500.00, 'employee_share' => 775.00, 'employer_share' => 0.00, 'total' => 775.00, 'status' => 'active'],
            ['id' => 27, 'salary_from' => 15750.00, 'salary_to' => 16249.99, 'monthly_salary_credit' => 16000.00, 'employee_share' => 800.00, 'employer_share' => 0.00, 'total' => 800.00, 'status' => 'active'],
            ['id' => 28, 'salary_from' => 16250.00, 'salary_to' => 16749.99, 'monthly_salary_credit' => 16500.00, 'employee_share' => 825.00, 'employer_share' => 0.00, 'total' => 825.00, 'status' => 'active'],
            ['id' => 29, 'salary_from' => 16750.00, 'salary_to' => 17249.99, 'monthly_salary_credit' => 17000.00, 'employee_share' => 850.00, 'employer_share' => 0.00, 'total' => 850.00, 'status' => 'active'],
            ['id' => 30, 'salary_from' => 17250.00, 'salary_to' => 17749.99, 'monthly_salary_credit' => 17500.00, 'employee_share' => 875.00, 'employer_share' => 0.00, 'total' => 875.00, 'status' => 'active'],
            ['id' => 31, 'salary_from' => 17750.00, 'salary_to' => 18249.99, 'monthly_salary_credit' => 18000.00, 'employee_share' => 900.00, 'employer_share' => 0.00, 'total' => 900.00, 'status' => 'active'],
            ['id' => 32, 'salary_from' => 18250.00, 'salary_to' => 18749.99, 'monthly_salary_credit' => 18500.00, 'employee_share' => 925.00, 'employer_share' => 0.00, 'total' => 925.00, 'status' => 'active'],
            ['id' => 33, 'salary_from' => 18750.00, 'salary_to' => 19249.99, 'monthly_salary_credit' => 19000.00, 'employee_share' => 950.00, 'employer_share' => 0.00, 'total' => 950.00, 'status' => 'active'],
            ['id' => 34, 'salary_from' => 19250.00, 'salary_to' => 19749.99, 'monthly_salary_credit' => 19500.00, 'employee_share' => 975.00, 'employer_share' => 0.00, 'total' => 975.00, 'status' => 'active'],
            ['id' => 35, 'salary_from' => 19750.00, 'salary_to' => 20249.99, 'monthly_salary_credit' => 20000.00, 'employee_share' => 1000.00, 'employer_share' => 0.00, 'total' => 1000.00, 'status' => 'active'],
            ['id' => 36, 'salary_from' => 20250.00, 'salary_to' => 20749.99, 'monthly_salary_credit' => 20500.00, 'employee_share' => 1000.00, 'employer_share' => 25.00, 'total' => 1025.00, 'status' => 'active'],
            ['id' => 37, 'salary_from' => 20750.00, 'salary_to' => 21249.99, 'monthly_salary_credit' => 21000.00, 'employee_share' => 1000.00, 'employer_share' => 50.00, 'total' => 1050.00, 'status' => 'active'],
            ['id' => 38, 'salary_from' => 21250.00, 'salary_to' => 21749.99, 'monthly_salary_credit' => 21500.00, 'employee_share' => 1000.00, 'employer_share' => 75.00, 'total' => 1075.00, 'status' => 'active'],
            ['id' => 39, 'salary_from' => 21750.00, 'salary_to' => 22249.99, 'monthly_salary_credit' => 22000.00, 'employee_share' => 1000.00, 'employer_share' => 100.00, 'total' => 1100.00, 'status' => 'active'],
            ['id' => 40, 'salary_from' => 22250.00, 'salary_to' => 22749.99, 'monthly_salary_credit' => 22500.00, 'employee_share' => 1000.00, 'employer_share' => 125.00, 'total' => 1125.00, 'status' => 'active'],
            ['id' => 41, 'salary_from' => 22750.00, 'salary_to' => 23249.99, 'monthly_salary_credit' => 23000.00, 'employee_share' => 1000.00, 'employer_share' => 150.00, 'total' => 1150.00, 'status' => 'active'],
            ['id' => 42, 'salary_from' => 23250.00, 'salary_to' => 23749.99, 'monthly_salary_credit' => 23500.00, 'employee_share' => 1000.00, 'employer_share' => 175.00, 'total' => 1175.00, 'status' => 'active'],
            ['id' => 43, 'salary_from' => 23750.00, 'salary_to' => 24249.99, 'monthly_salary_credit' => 24000.00, 'employee_share' => 1000.00, 'employer_share' => 200.00, 'total' => 1200.00, 'status' => 'active'],
            ['id' => 44, 'salary_from' => 24250.00, 'salary_to' => 24749.99, 'monthly_salary_credit' => 24500.00, 'employee_share' => 1000.00, 'employer_share' => 225.00, 'total' => 1225.00, 'status' => 'active'],
            ['id' => 45, 'salary_from' => 24750.00, 'salary_to' => 25249.99, 'monthly_salary_credit' => 25000.00, 'employee_share' => 1000.00, 'employer_share' => 250.00, 'total' => 1250.00, 'status' => 'active'],
            ['id' => 46, 'salary_from' => 25250.00, 'salary_to' => 25749.99, 'monthly_salary_credit' => 25500.00, 'employee_share' => 1000.00, 'employer_share' => 275.00, 'total' => 1275.00, 'status' => 'active'],
            ['id' => 47, 'salary_from' => 25750.00, 'salary_to' => 26249.99, 'monthly_salary_credit' => 26000.00, 'employee_share' => 1000.00, 'employer_share' => 300.00, 'total' => 1300.00, 'status' => 'active'],
            ['id' => 48, 'salary_from' => 26250.00, 'salary_to' => 26749.99, 'monthly_salary_credit' => 26500.00, 'employee_share' => 1000.00, 'employer_share' => 325.00, 'total' => 1325.00, 'status' => 'active'],
            ['id' => 49, 'salary_from' => 26750.00, 'salary_to' => 27249.99, 'monthly_salary_credit' => 27000.00, 'employee_share' => 1000.00, 'employer_share' => 350.00, 'total' => 1350.00, 'status' => 'active'],
            ['id' => 50, 'salary_from' => 27250.00, 'salary_to' => 27749.99, 'monthly_salary_credit' => 27500.00, 'employee_share' => 1000.00, 'employer_share' => 375.00, 'total' => 1375.00, 'status' => 'active'],
            ['id' => 51, 'salary_from' => 27750.00, 'salary_to' => 28249.99, 'monthly_salary_credit' => 28000.00, 'employee_share' => 1000.00, 'employer_share' => 400.00, 'total' => 1400.00, 'status' => 'active'],
            ['id' => 52, 'salary_from' => 28250.00, 'salary_to' => 28749.99, 'monthly_salary_credit' => 28500.00, 'employee_share' => 1000.00, 'employer_share' => 425.00, 'total' => 1425.00, 'status' => 'active'],
            ['id' => 53, 'salary_from' => 28750.00, 'salary_to' => 29249.99, 'monthly_salary_credit' => 29000.00, 'employee_share' => 1000.00, 'employer_share' => 450.00, 'total' => 1450.00, 'status' => 'active'],
            ['id' => 54, 'salary_from' => 29250.00, 'salary_to' => 29749.99, 'monthly_salary_credit' => 29500.00, 'employee_share' => 1000.00, 'employer_share' => 475.00, 'total' => 1475.00, 'status' => 'active'],
            ['id' => 55, 'salary_from' => 29750.00, 'salary_to' => 30249.99, 'monthly_salary_credit' => 30000.00, 'employee_share' => 1000.00, 'employer_share' => 500.00, 'total' => 1500.00, 'status' => 'active'],
            ['id' => 56, 'salary_from' => 30250.00, 'salary_to' => 30749.99, 'monthly_salary_credit' => 30500.00, 'employee_share' => 1000.00, 'employer_share' => 525.00, 'total' => 1525.00, 'status' => 'active'],
            ['id' => 57, 'salary_from' => 30750.00, 'salary_to' => 31249.99, 'monthly_salary_credit' => 31000.00, 'employee_share' => 1000.00, 'employer_share' => 550.00, 'total' => 1550.00, 'status' => 'active'],
            ['id' => 58, 'salary_from' => 31250.00, 'salary_to' => 31749.99, 'monthly_salary_credit' => 31500.00, 'employee_share' => 1000.00, 'employer_share' => 575.00, 'total' => 1575.00, 'status' => 'active'],
            ['id' => 59, 'salary_from' => 31750.00, 'salary_to' => 32249.99, 'monthly_salary_credit' => 32000.00, 'employee_share' => 1000.00, 'employer_share' => 600.00, 'total' => 1600.00, 'status' => 'active'],
            ['id' => 60, 'salary_from' => 32250.00, 'salary_to' => 32749.99, 'monthly_salary_credit' => 32500.00, 'employee_share' => 1000.00, 'employer_share' => 625.00, 'total' => 1625.00, 'status' => 'active'],
            ['id' => 61, 'salary_from' => 32750.00, 'salary_to' => 33249.99, 'monthly_salary_credit' => 33000.00, 'employee_share' => 1000.00, 'employer_share' => 650.00, 'total' => 1650.00, 'status' => 'active'],
            ['id' => 62, 'salary_from' => 33250.00, 'salary_to' => 33749.99, 'monthly_salary_credit' => 33500.00, 'employee_share' => 1000.00, 'employer_share' => 675.00, 'total' => 1675.00, 'status' => 'active'],
            ['id' => 63, 'salary_from' => 33750.00, 'salary_to' => 34249.99, 'monthly_salary_credit' => 34000.00, 'employee_share' => 1000.00, 'employer_share' => 700.00, 'total' => 1700.00, 'status' => 'active'],
            ['id' => 64, 'salary_from' => 34250.00, 'salary_to' => 34749.99, 'monthly_salary_credit' => 34500.00, 'employee_share' => 1000.00, 'employer_share' => 725.00, 'total' => 1725.00, 'status' => 'active'],
            ['id' => 65, 'salary_from' => 34750.00, 'salary_to' => 9999999.99, 'monthly_salary_credit' => 35000.00, 'employee_share' => 1000.00, 'employer_share' => 750.00, 'total' => 1750.00, 'status' => 'active'],
        ];

        DB::table('sss_contributions')->insert($contributions);
    }
}