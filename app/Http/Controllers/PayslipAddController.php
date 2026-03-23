<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayslipAddController extends Controller
{
    /* ==============================================
       SHOW ADD FORM
    ============================================== */
    public function create()
    {
        $employees = DB::table('employees')
        ->leftJoin('departments','employees.department_id','=','departments.id')
        ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
        
        ->select(
            'employees.*',
            'departments.name as department_name',
            'job_titles.title as job_title'
        )
        ->get();

        $periods = DB::table('payroll_periods')
            ->whereIn('status', ['open', 'for_approval'])
            ->get();

        return view('payslip_add', compact('employees','periods'));
    }

    /* ==============================================x
       STORE PAYSLIP
    ============================================== */
    public function store(Request $request)
    {
    $request->validate([
        'employee_id' => 'required',
        'payroll_period_id' => 'required',
        'leave_days' => 'nullable|numeric',
        'earnings_name' => 'array',
        'earnings_amount' => 'array',
        'deductions_name' => 'array',
        'deductions_amount' => 'array'
    ]);

        $employee = DB::table('employees')
            ->where('employee_id',$request->employee_id)
            ->first();

        $period = DB::table('payroll_periods')
            ->where('id',$request->payroll_period_id)
            ->whereIn('status',['open','for_approval'])
            ->first();

        if(!$employee || !$period){
            return back()->with('error','Invalid employee or payroll period');
        }

        /* ==============================================
           PREVENT DUPLICATE PAYSLIP
        ============================================== */

        $existing = DB::table('payroll_records')
            ->where('employee_id',$employee->employee_id)
            ->where('payroll_period_id',$period->id)
            ->first();

        if($existing){
            return back()->with('error','Payslip already exists for this employee in this period.');
        }

        /* ==============================================
           BASIC PAY COMPUTATION
        ============================================== */

        $start = Carbon::parse($period->start_date);
        $end = Carbon::parse($period->end_date);
        $days = $start->diffInDays($end) + 1;

        $dailyRate = $employee->basic_salary / 30;
        $grossPay = $dailyRate * $days;

        /* ==============================================
   ATTENDANCE COMPUTATION
============================================== */

$attendanceLogs = DB::table('attendance_logs')
    ->where('employee_id', $employee->employee_id)
    ->whereBetween('attendance_date', [
        $period->start_date,
        $period->end_date
    ])
    ->get();

$activeSettings = DB::table('payroll_items_settings')
    ->where('is_active',1)
    ->get();

$attendanceEarnings = 0;
$attendanceDeductions = 0;
$attendanceItems = [];

/* salary rates */
$dailyRate = $employee->basic_salary / 30;
$hourlyRate = $dailyRate / 8;
$minuteRate = $hourlyRate / 60;

foreach($activeSettings as $setting){

    $totalUnits = 0;

    foreach($attendanceLogs as $log){

        switch($setting->category){

            case 'late':
                $totalUnits += $log->late_minutes;
                break;

            case 'undertime':
                $totalUnits += $log->undertime_minutes;
                break;

            case 'overtime':
                $totalUnits += $log->overtime_minutes;
                break;

            case 'absent':
                if($log->is_absent){
                    $totalUnits += 1;
                }
                break;

            case 'holiday':
                if(isset($log->is_holiday) && $log->is_holiday){
                    $totalUnits += 1;
                }
                break;

            case 'leave':
                break;
        }
    }

    if($totalUnits <= 0) continue;

    /* compute amount based on basis */

    switch($setting->basis){

        case 'per_minute':
            $amount = $minuteRate * $totalUnits * $setting->multiplier;
            break;

        case 'per_hour':
            $amount = $hourlyRate * ($totalUnits / 60) * $setting->multiplier;
            break;

        case 'per_day':
            $amount = $dailyRate * $totalUnits * $setting->multiplier;
            break;

        default:
            $amount = 0;
    }

    if($setting->type == 'earning'){
        $attendanceEarnings += $amount;
    }else{
        $attendanceDeductions += $amount;
    }

    $attendanceItems[] = [
        'type' => $setting->type,
        'name' => $setting->name,
        'amount' => $amount
    ];
}

        /* ==============================================
           GOVERNMENT CONTRIBUTIONS
        ============================================== */

        $monthlySalary = $employee->basic_salary;

        // SSS
        $sssRow = DB::table('sss_contributions')
            ->where('salary_from', '<=', $monthlySalary)
            ->where('salary_to', '>=', $monthlySalary)
            ->first();

        $monthlySSS = $sssRow ?($sssRow->employee_share + $sssRow->employer_share) : 0;

        //Philhealth
        $philhealthRow = DB::table('philhealth_contributions')->first();

        $monthlyPhilHealth = 0;

        if($philhealthRow){

            $salaryForPhilhealth = $monthlySalary;

            if($philhealthRow->min_salary && $salaryForPhilhealth < $philhealthRow->min_salary){
                $salaryForPhilhealth = $philhealthRow->min_salary;
            }

            if($philhealthRow->max_salary && $salaryForPhilhealth > $philhealthRow->max_salary){
                $salaryForPhilhealth = $philhealthRow->max_salary;
            }

            $monthlyPhilHealth =
                $salaryForPhilhealth *
                ($philhealthRow->contribution_rate / 100);
        }

        // Pag-IBIG
        $pagibigRow = DB::table('pagibig_contributions')->first();

        $monthlyPagibig = 0;

        if($pagibigRow){

            $salaryForPagibig = min(
                $monthlySalary,
                $pagibigRow->salary_cap
            );

            if($monthlySalary <= $pagibigRow->salary_threshold){

                $monthlyPagibig =
                    $salaryForPagibig *
                    ($pagibigRow->employee_rate_low / 100);

            }else{

                $monthlyPagibig =
                    $salaryForPagibig *
                    ($pagibigRow->employee_rate_high / 100);

            }
        }

        // TAX
        $annual = $monthlySalary * 12;

        $taxRow = DB::table('tax_contributions')
            ->where('income_from', '<=', $annual)
            ->where(function ($q) use ($annual) {
                $q->where('income_to', '>=', $annual)
                  ->orWhereNull('income_to');
            })
            ->first();

        $annualTax = 0;

        if ($taxRow && $annual > 250000) {
            $annualTax = $taxRow->base_tax +
                (($annual - $taxRow->excess_over) * $taxRow->tax_rate);
        }

        $monthlyTax = $annualTax / 12;

        $sss = $monthlySSS / 2;
        $philhealth = $monthlyPhilHealth / 2;
        $pagibig = $monthlyPagibig / 2;
        $tax = $monthlyTax / 2;

        $statutoryTotal = $sss + $philhealth + $pagibig + $tax;


/* ==============================================
INITIALIZE MANUAL VARIABLES
============================================== */

$manualEarnings = 0;
$manualDeductions = 0;

$manualEarningItems = [];
$manualDeductionItems = [];


/* ==============================================
MANUAL LEAVE PAY (FROM FORM)
============================================== */

$leaveDaysInput = min(($request->leave_days ?? 0), $days);

$leavePay = $leaveDaysInput * $dailyRate;

if($leavePay > 0){

    $manualEarnings += $leavePay;

    $manualEarningItems[] = [
        'type' => 'earning',
        'name' => 'Leave Pay (' . $leaveDaysInput . ' days)',
        'amount' => $leavePay
    ];
}


/* ==============================================
MANUAL EARNINGS
============================================== */

if($request->earnings_name){

    foreach($request->earnings_name as $index => $name){

        $amount = $request->earnings_amount[$index] ?? 0;

        if(!$name || $amount <= 0) continue;

        $manualEarnings += $amount;

        $manualEarningItems[] = [
            'type' => 'earning',
            'name' => $name,
            'amount' => $amount
        ];
    }

}


/* ==============================================
   MANUAL DEDUCTIONS
============================================== */

$manualDeductions = 0;
$manualDeductionItems = [];

if($request->deductions_name){

    foreach($request->deductions_name as $index => $name){

        $amount = $request->deductions_amount[$index] ?? 0;

        if(!$name || $amount <= 0) continue;

        $manualDeductions += $amount;

        $manualDeductionItems[] = [
            'type' => 'deduction',
            'name' => $name,
            'amount' => $amount
        ];
    }

}


/* ==============================================
   FINAL TOTALS
============================================== */

$totalEarnings =
    $attendanceEarnings +
    $manualEarnings;

$totalDeductions =
    $attendanceDeductions +
    $manualDeductions +
    $statutoryTotal;

$netPay =
    $grossPay +
    $totalEarnings -
    $totalDeductions;

        /* ==============================================
           INSERT PAYROLL RECORD
        ============================================== */

        $payrollRecordId = DB::table('payroll_records')->insertGetId([
            'payroll_period_id' => $period->id,
            'employee_id' => $employee->employee_id,
            'basic_salary' => $employee->basic_salary,
            'gross_pay' => $grossPay,
            'total_earnings' => $totalEarnings,
            'total_deductions' => $totalDeductions,
            'net_pay' => $netPay,
            'status' => 'draft',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // ===========================
        // GENERATE PAYROLL CODE
        // ===========================
$yearMonth = Carbon::parse($period->start_date)->format('Ym'); // YYYYMM

// Generate 4 random digits
$randomDigits = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

$payrollCode = 'MS-' . $yearMonth . '-' . $randomDigits;

// Update the payroll record with the payroll code
DB::table('payroll_records')
    ->where('id', $payrollRecordId)
    ->update(['payroll_code' => $payrollCode]);

        /* ==============================================
           SAVE ATTENDANCE ITEMS
        ============================================== */

        foreach($attendanceItems as $item){
            DB::table('payroll_items')->insert([
                'payroll_record_id' => $payrollRecordId,
                'type' => $item['type'],
                'name' => $item['name'],
                'amount' => $item['amount'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

/* ==============================================
   SAVE MANUAL EARNINGS
============================================== */

foreach($manualEarningItems as $item){

    DB::table('payroll_items')->insert([
        'payroll_record_id' => $payrollRecordId,
        'type' => $item['type'],
        'name' => $item['name'],
        'amount' => $item['amount'],
        'created_at' => now(),
        'updated_at' => now()
    ]);

}


/* ==============================================
   SAVE MANUAL DEDUCTIONS
============================================== */

foreach($manualDeductionItems as $item){

    DB::table('payroll_items')->insert([
        'payroll_record_id' => $payrollRecordId,
        'type' => $item['type'],
        'name' => $item['name'],
        'amount' => $item['amount'],
        'created_at' => now(),
        'updated_at' => now()
    ]);

}

        /* ==============================================
           SAVE STATUTORY ITEMS
        ============================================== */

        $statutoryItems = [
            ['name' => 'SSS', 'amount' => $sss],
            ['name' => 'PhilHealth', 'amount' => $philhealth],
            ['name' => 'Pag-IBIG', 'amount' => $pagibig],
            ['name' => 'Withholding Tax', 'amount' => $tax],
        ];

        foreach($statutoryItems as $item){
            if($item['amount'] > 0){
                DB::table('payroll_items')->insert([
                    'payroll_record_id' => $payrollRecordId,
                    'type' => 'deduction',
                    'name' => $item['name'],
                    'amount' => $item['amount'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return redirect('/payslip')
            ->with('success','Payslip created successfully with attendance integration.');
    }

public function compute(Request $request)
{
    $employee = DB::table('employees')
        ->where('employee_id', $request->employee_id)
        ->first();

    if (!$employee) {
        return response()->json(['error' => 'Employee not found'], 404);
    }

    $monthlySalary = $employee->basic_salary;
    $biWeeklySalary = $monthlySalary / 2;

    // SSS
    $sssRow = DB::table('sss_contributions')
        ->where('salary_from', '<=', $monthlySalary)
        ->where('salary_to', '>=', $monthlySalary)
        ->first();

    $monthlySSS = $sssRow ?($sssRow->employee_share + $sssRow->employer_share) : 0;

    // PhilHealth (5%)
    $philhealthRow = DB::table('philhealth_contributions')->first();

    $monthlyPhilHealth = 0;

    if($philhealthRow){

        $salaryForPhilhealth = $monthlySalary;

        if($philhealthRow->min_salary && $salaryForPhilhealth < $philhealthRow->min_salary){
            $salaryForPhilhealth = $philhealthRow->min_salary;
        }

        if($philhealthRow->max_salary && $salaryForPhilhealth > $philhealthRow->max_salary){
            $salaryForPhilhealth = $philhealthRow->max_salary;
        }

        $monthlyPhilHealth =
            $salaryForPhilhealth *
            ($philhealthRow->contribution_rate / 100);
    }

    // Pag-IBIG (2% max 10k)
    $pagibigRow = DB::table('pagibig_contributions')->first();

$monthlyPagibig = 0;

if($pagibigRow){

    $salaryForPagibig = min(
        $monthlySalary,
        $pagibigRow->salary_cap
    );

    if($monthlySalary <= $pagibigRow->salary_threshold){

        $monthlyPagibig =
            $salaryForPagibig *
            ($pagibigRow->employee_rate_low / 100);

    }else{

        $monthlyPagibig =
            $salaryForPagibig *
            ($pagibigRow->employee_rate_high / 100);

    }
}

    // TAX
    $annual = $monthlySalary * 12;

    $taxRow = DB::table('tax_contributions')
        ->where('income_from', '<=', $annual)
        ->where(function ($q) use ($annual) {
            $q->where('income_to', '>=', $annual)
              ->orWhereNull('income_to');
        })
        ->first();

    $annualTax = 0;

    if ($taxRow && $annual > 250000) {
        $annualTax = $taxRow->base_tax +
            (($annual - $taxRow->excess_over) * $taxRow->tax_rate);
    }

    $monthlyTax = $annualTax / 12;

    // BI-WEEKLY
    $biSSS = $monthlySSS / 2;
    $biPhilHealth = $monthlyPhilHealth / 2;
    $biPagibig = $monthlyPagibig / 2;
    $biTax = $monthlyTax / 2;

    return response()->json([
        'bi_sss' => number_format($biSSS,2),
        'bi_philhealth' => number_format($biPhilHealth,2),
        'bi_pagibig' => number_format($biPagibig,2),
        'bi_tax' => number_format($biTax,2),

        // RAW VALUES FOR SAVING
        'bi_sss_raw' => $biSSS,
        'bi_philhealth_raw' => $biPhilHealth,
        'bi_pagibig_raw' => $biPagibig,
        'bi_tax_raw' => $biTax,
    ]);
}

public function attendancePreview(Request $request)
{
    $employee = DB::table('employees')
        ->where('employee_id', $request->employee_id)
        ->first();

    if (!$employee) {
        return response()->json(['error' => 'Employee not found'], 404);
    }

    $period = DB::table('payroll_periods')
        ->where('id', $request->period_id)
        ->first();

    if (!$period) {
        return response()->json(['error' => 'Period not found'], 404);
    }

    $logs = DB::table('attendance_logs')
        ->where('employee_id', $employee->employee_id)
        ->whereBetween('attendance_date', [
            $period->start_date,
            $period->end_date
        ])
        ->get();

    $late = $logs->sum('late_minutes');
    $undertime = $logs->sum('undertime_minutes');
    $overtime = $logs->sum('overtime_minutes');
    $absent = $logs->where('is_absent', 1)->count();
    $leave = $logs->where('is_leave', 1)->count();

        return response()->json([
        'late' => $late,
        'undertime' => $undertime,
        'overtime' => $overtime,
        'absent' => $absent,
        'leave' => $leave
    ]);
}

public function netPreview(Request $request)
{

    $employee = DB::table('employees')
        ->where('employee_id',$request->employee_id)
        ->first();

    $period = DB::table('payroll_periods')
        ->where('id',$request->period_id)
        ->first();

    if(!$employee || !$period){
        return response()->json(['net'=>0]);
    }

    $start = Carbon::parse($period->start_date);
    $end = Carbon::parse($period->end_date);
    $days = $start->diffInDays($end) + 1;

    $dailyRate = $employee->basic_salary / 30;
    $hourlyRate = $dailyRate / 8;
    $minuteRate = $hourlyRate / 60;

    $grossPay = $dailyRate * $days;

    /* =========================================
       ATTENDANCE
    ========================================= */

    $logs = DB::table('attendance_logs')
        ->where('employee_id',$employee->employee_id)
        ->whereBetween('attendance_date',[
            $period->start_date,
            $period->end_date
        ])
        ->get();

    $settings = DB::table('payroll_items_settings')
        ->where('is_active',1)
        ->get();

    $attendanceEarnings = 0;
    $attendanceDeductions = 0;

    foreach($settings as $setting){

        $units = 0;

        foreach($logs as $log){

            switch($setting->category){

                case 'late':
                    $units += $log->late_minutes;
                    break;

                case 'undertime':
                    $units += $log->undertime_minutes;
                    break;

                case 'overtime':
                    $units += $log->overtime_minutes;
                    break;

                case 'absent':
                    if($log->is_absent) $units++;
                    break;

                case 'holiday':
                    if(isset($log->is_holiday) && $log->is_holiday) $units++;
                    break;
            }
        }

        if($units <= 0) continue;

        switch($setting->basis){

            case 'per_minute':
                $amount = $minuteRate * $units * $setting->multiplier;
                break;

            case 'per_hour':
                $amount = $hourlyRate * ($units / 60) * $setting->multiplier;
                break;

            case 'per_day':
                $amount = $dailyRate * $units * $setting->multiplier;
                break;

            default:
                $amount = 0;
        }

        if($setting->type == 'earning'){
            $attendanceEarnings += $amount;
        }else{
            $attendanceDeductions += $amount;
        }

    }

    /* =========================================
       STATUTORY
    ========================================= */

    $monthlySalary = $employee->basic_salary;

    // SSS
    $sssRow = DB::table('sss_contributions')
        ->where('salary_from','<=',$monthlySalary)
        ->where('salary_to','>=',$monthlySalary)
        ->first();

    $monthlySSS = $sssRow ? ($sssRow->employee_share + $sssRow->employer_share) : 0;

    // PhilHealth
    $philhealthRow = DB::table('philhealth_contributions')->first();
    $monthlyPhilHealth = 0;

    if($philhealthRow){

        $salaryPH = $monthlySalary;

        if($salaryPH < $philhealthRow->min_salary){
            $salaryPH = $philhealthRow->min_salary;
        }

        if($salaryPH > $philhealthRow->max_salary){
            $salaryPH = $philhealthRow->max_salary;
        }

        $monthlyPhilHealth = $salaryPH * ($philhealthRow->contribution_rate / 100);
    }

    // PagIBIG
    $pagibigRow = DB::table('pagibig_contributions')->first();
    $monthlyPagibig = 0;

    if($pagibigRow){

        $salaryPI = min($monthlySalary,$pagibigRow->salary_cap);

        if($monthlySalary <= $pagibigRow->salary_threshold){
            $monthlyPagibig = $salaryPI * ($pagibigRow->employee_rate_low / 100);
        }else{
            $monthlyPagibig = $salaryPI * ($pagibigRow->employee_rate_high / 100);
        }
    }

    // TAX
    $annual = $monthlySalary * 12;

    $taxRow = DB::table('tax_contributions')
        ->where('income_from','<=',$annual)
        ->where(function ($q) use ($annual) {
            $q->where('income_to','>=',$annual)
              ->orWhereNull('income_to');
        })
        ->first();

    $annualTax = 0;

    if ($taxRow && $annual > 250000) {
        $annualTax = $taxRow->base_tax +
            (($annual - $taxRow->excess_over) * $taxRow->tax_rate);
    }

    $monthlyTax = $annualTax / 12;

    $sss = $monthlySSS / 2;
    $philhealth = $monthlyPhilHealth / 2;
    $pagibig = $monthlyPagibig / 2;
    $tax = $monthlyTax / 2;

    $statutoryTotal = $sss + $philhealth + $pagibig + $tax;

    /* =========================================
       FINAL
    ========================================= */

    $totalEarnings = $attendanceEarnings;
    $totalDeductions = $attendanceDeductions + $statutoryTotal;

    $net = $grossPay + $totalEarnings - $totalDeductions;

    return response()->json([
        'net' => number_format($net,2)
    ]);
}
}