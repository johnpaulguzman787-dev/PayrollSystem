<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollPeriodController extends Controller
{
public function index()
{
    $periods = DB::table('payroll_periods')->orderByDesc('id')->get();

    /*
    =====================================
    GET LATEST PAYROLL PERIOD WITH RECORDS
    =====================================
    */

    $currentPeriod = DB::table('payroll_records')
        ->select('payroll_period_id')
        ->orderByDesc('payroll_period_id')
        ->first();

    $gross = 0;
    $net = 0;
    $deductions = 0;
    $employees = 0;
    $netTrend = 0;

    if ($currentPeriod) {

        $periodId = $currentPeriod->payroll_period_id;

        $gross = DB::table('payroll_records')
            ->where('payroll_period_id', $periodId)
            ->sum('gross_pay');

        $net = DB::table('payroll_records')
            ->where('payroll_period_id', $periodId)
            ->sum('net_pay');

        $deductions = DB::table('payroll_records')
            ->where('payroll_period_id', $periodId)
            ->sum('total_deductions');

        $employees = DB::table('payroll_records')
            ->where('payroll_period_id', $periodId)
            ->count();


        /*
        ==========================
        PREVIOUS PERIOD TREND
        ==========================
        */

        $previousPeriod = DB::table('payroll_records')
            ->where('payroll_period_id','<',$periodId)
            ->orderByDesc('payroll_period_id')
            ->first();

        if ($previousPeriod) {

            $previousNet = DB::table('payroll_records')
                ->where('payroll_period_id',$previousPeriod->payroll_period_id)
                ->sum('net_pay');

            $netTrend = $net - $previousNet;
        }
    }


    /*
    ==========================
    PAYROLL TREND GRAPH
    ==========================
    */

    $trendPeriods = DB::table('payroll_records')
        ->select('payroll_period_id')
        ->distinct()
        ->orderByDesc('payroll_period_id')
        ->limit(6)
        ->get()
        ->reverse();

    $trendLabels = [];
    $trendData = [];
    $deductionTrend = [];

    foreach ($trendPeriods as $p) {

        $period = DB::table('payroll_periods')
            ->where('id',$p->payroll_period_id)
            ->first();

        $totalNet = DB::table('payroll_records')
            ->where('payroll_period_id',$p->payroll_period_id)
            ->sum('net_pay');

        $totalDeduction = DB::table('payroll_records')
            ->where('payroll_period_id',$p->payroll_period_id)
            ->sum('total_deductions');

        $trendLabels[] = $period ? $period->name : "Period ".$p->payroll_period_id;
        $trendData[] = $totalNet;
        $deductionTrend[] = $totalDeduction;
    }


    /*
    ==========================
    DEPARTMENT DISTRIBUTION
    ==========================
    */

    $departmentData = DB::table('employees')
        ->join('departments', 'employees.department_id', '=', 'departments.id')
        ->select(
            'departments.name',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('departments.name')
        ->get();

    $deptLabels = $departmentData->pluck('name');
    $deptTotals = $departmentData->pluck('total');


    return view('payroll_period', compact(
        'periods',
        'gross',
        'net',
        'deductions',
        'employees',
        'netTrend',
        'trendLabels',
        'trendData',
        'deductionTrend',
        'deptLabels',
        'deptTotals'
    ));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $overlap = DB::table('payroll_periods')
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($overlap) {
            return redirect('/payroll-period')->with('error', 'Payroll period overlaps with an existing one.');
        }

        $payroll_code = str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        DB::table('payroll_periods')->insert([
            'name' => $request->name,
            'payroll_code' => $payroll_code,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'draft'
        ]);

        return redirect('/payroll-period')->with('success', 'Payroll period created successfully.');
    }

    public function update(Request $request, $id)
    {
        $period = DB::table('payroll_periods')->where('id', $id)->first();

        if (!$period) {
            return redirect('/payroll-period')->with('error', 'Payroll period not found.');
        }

        if ($period->status != 'draft') {
            return redirect('/payroll-period')->with('error', 'Only draft payroll periods can be edited.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:draft,open,for_approval,approved,closed'
        ]);

        $overlap = DB::table('payroll_periods')
            ->where('id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($overlap) {
            return redirect('/payroll-period')->with('error', 'Payroll period overlaps with an existing one.');
        }

        DB::table('payroll_periods')->where('id', $id)->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ]);

        return redirect('/payroll-period')->with('success', 'Payroll period updated successfully.');
    }

    public function delete($id)
    {
        DB::table('payroll_periods')->where('id', $id)->delete();
        return redirect('/payroll-period')->with('success', 'Payroll period deleted successfully.');
    }

    public function changeStatus(Request $request, $id)
{
    $period = DB::table('payroll_periods')->where('id', $id)->first();

    if (!$period) {
        return redirect('/payroll-period')->with('error', 'Payroll period not found.');
    }

    $allowed = [
        'draft' => 'open',
        'open' => 'for_approval',
        'for_approval' => 'approved',
        'approved' => 'closed'
    ];

    if (!isset($allowed[$period->status])) {
        return redirect('/payroll-period')->with('error', 'No next status available.');
    }

    $newStatus = $allowed[$period->status];

    DB::table('payroll_periods')
        ->where('id', $id)
        ->update(['status' => $newStatus]);



    /*
    =====================================
    AUTO CREATE NEXT PAYROLL PERIOD
    RUN WHEN: draft → open
    =====================================
    */

    if ($period->status == 'draft' && $newStatus == 'open') {

        $start = new Carbon($period->start_date);
        $end = new Carbon($period->end_date);
        $days = $start->diffInDays($end) + 1;

        $nextStart = $end->copy()->addDay();
        $nextEnd = $nextStart->copy()->addDays($days - 1);

        $exists = DB::table('payroll_periods')
            ->where('start_date', $nextStart->format('Y-m-d'))
            ->exists();

        if (!$exists) {

            $payroll_code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

            $nextName = 'Payroll ' . $nextStart->format('M j') . ' - ' . $nextEnd->format('M j');

            DB::table('payroll_periods')->insert([
                'name' => $nextName,
                'payroll_code' => $payroll_code,
                'start_date' => $nextStart->format('Y-m-d'),
                'end_date' => $nextEnd->format('Y-m-d'),
                'status' => 'draft'
            ]);
        }
    }



    /*
    =====================================
    AUTO GENERATE PAYROLL RECORDS
    RUN WHEN: open → for_approval
    =====================================
    */

    if ($period->status == 'open' && $newStatus == 'for_approval') {

        $start = Carbon::parse($period->start_date);
        $end = Carbon::parse($period->end_date);
        $days = $start->diffInDays($end) + 1;

        $employees = DB::table('employees')->get();

        foreach ($employees as $employee) {

            $dailyRate = $employee->basic_salary / 30;
            $hourlyRate = $dailyRate / 8;
            $minuteRate = $hourlyRate / 60;

            $grossPay = $dailyRate * $days;

            $attendanceLogs = DB::table('attendance_logs')
                ->where('employee_id', $employee->employee_id)
                ->whereBetween('attendance_date', [
                    $period->start_date,
                    $period->end_date
                ])
                ->get();

            $activeSettings = DB::table('payroll_items_settings')
                ->where('is_active', 1)
                ->get();

            $attendanceEarnings = 0;
            $attendanceDeductions = 0;
            $attendanceItems = [];

            foreach ($activeSettings as $setting) {

                $totalUnits = 0;

                foreach ($attendanceLogs as $log) {

                    switch ($setting->category) {

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
                            if ($log->is_absent) {
                                $totalUnits += 1;
                            }
                            break;

                        case 'holiday':
                            if (isset($log->is_holiday) && $log->is_holiday) {
                                $totalUnits += 1;
                            }
                            break;
                    }
                }

                if ($totalUnits <= 0) continue;

                switch ($setting->basis) {

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

                if ($setting->type == 'earning') {
                    $attendanceEarnings += $amount;
                } else {
                    $attendanceDeductions += $amount;
                }

                $attendanceItems[] = [
                    'type' => $setting->type,
                    'name' => $setting->name,
                    'amount' => $amount
                ];
            }



            $monthlySalary = $employee->basic_salary;

        //SSS
            $sssRow = DB::table('sss_contributions')
                ->where('salary_from', '<=', $monthlySalary)
                ->where('salary_to', '>=', $monthlySalary)
                ->first();

            $monthlySSS = $sssRow
                ? ($sssRow->employee_share + $sssRow->employer_share)
                : 0;

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

        //TAX
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

            $totalEarnings = $attendanceEarnings;

            $totalDeductions =
                $attendanceDeductions +
                $statutoryTotal;

            $netPay =
                $grossPay +
                $totalEarnings -
                $totalDeductions;



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

$yearMonth = Carbon::parse($period->start_date)->format('Ym'); // YYYYMM

// Generate 4 random digits
$randomDigits = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

$payrollCode = 'MS-' . $yearMonth . '-' . $randomDigits;

// Update the payroll record with the payroll code
DB::table('payroll_records')
    ->where('id', $payrollRecordId)
    ->update(['payroll_code' => $payrollCode]);



            foreach ($attendanceItems as $item) {
                DB::table('payroll_items')->insert([
                    'payroll_record_id' => $payrollRecordId,
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'amount' => $item['amount'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }



            $statutoryItems = [
                ['name' => 'SSS', 'amount' => $sss],
                ['name' => 'PhilHealth', 'amount' => $philhealth],
                ['name' => 'Pag-IBIG', 'amount' => $pagibig],
                ['name' => 'Withholding Tax', 'amount' => $tax],
            ];

            foreach ($statutoryItems as $item) {
                if ($item['amount'] > 0) {
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
        }
    }

    return redirect('/payroll-period')
        ->with('success', 'Payroll period status updated successfully.');
}

public function view($id)
{
    $period = DB::table('payroll_periods')->where('id', $id)->first();

    if (!$period) {
        return response()->json(['error' => 'Payroll period not found'], 404);
    }

    $records = DB::table('payroll_records')
    ->where('payroll_period_id', $id)
    ->where('status', 'paid')   // <-- only paid records
    ->get();

    $gross = $records->sum('gross_pay');
    $net = $records->sum('net_pay');
    $deductions = $records->sum('total_deductions');
    $employees = $records->count();

    // Department breakdown
    $departments = DB::table('payroll_records')
        ->join('employees','payroll_records.employee_id','=','employees.employee_id')
        ->join('departments','employees.department_id','=','departments.id')
        ->select('departments.name', DB::raw('SUM(payroll_records.net_pay) as total'))
        ->where('payroll_period_id', $id)
        ->where('payroll_records.status', 'paid')  // <-- only paid
        ->groupBy('departments.name')
        ->get();

    return response()->json([
        'name' => $period->name,
        'start_date' => Carbon::parse($period->start_date)->format('F j, Y'),
        'end_date' => Carbon::parse($period->end_date)->format('F j, Y'),
        'status' => str_replace('_',' ', $period->status),
        'gross' => $gross,
        'net' => $net,
        'deductions' => $deductions,
        'employees' => $employees,
        'departments' => $departments
    ]);
}
}