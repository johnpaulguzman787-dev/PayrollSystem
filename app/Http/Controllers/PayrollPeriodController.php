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
        $currentPeriod = DB::table('payroll_periods')
            ->where('status', 'pending')
            ->orderByRaw("CASE WHEN status = 'submitted' THEN 1 WHEN status = 'open' THEN 2 END")
            ->orderByDesc('id')
            ->first();

        $gross = 0; $net = 0; $deductions = 0; $employees = 0; $netTrend = 0; $daysToCutoff = 0;
        $currentPeriodName = null;

        if ($currentPeriod) {
            $periodId = $currentPeriod->id;
            $gross = DB::table('payslips')->where('payroll_period_id', $periodId)->sum('gross_pay');
            $net = DB::table('payslips')->where('payroll_period_id', $periodId)->sum('net_pay');
            $deductions = DB::table('payslips')->where('payroll_period_id', $periodId)->sum('total_deductions');
            $employees = DB::table('payslips')->where('payroll_period_id', $periodId)->count();
            $today = Carbon::now();
            $end = Carbon::parse($currentPeriod->end_date);
            $daysToCutoff = max(0, (int)$today->diffInDays($end, false));
            $currentPeriodName = $currentPeriod->name;

            $previousPeriod = DB::table('payslips')
                ->where('payroll_period_id', '<', $periodId)
                ->orderByDesc('payroll_period_id')
                ->first();
            if ($previousPeriod) {
                $previousNet = DB::table('payslips')
                    ->where('payroll_period_id', $previousPeriod->payroll_period_id)
                    ->sum('net_pay');
                $netTrend = $net - $previousNet;
            }
        }

        $years = DB::table('payroll_periods')
            ->selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $salaryGrades = DB::table('salary_grades')
            ->select('salary_grades.*', DB::raw('(SELECT COUNT(*) FROM employees WHERE employees.salary_grade_id = salary_grades.id) as employees_count'))
            ->get();

        $allEmployees = DB::table('employees')->get();
        $benefits = DB::table('benefits')->get();
        $departments = DB::table('departments')->get();

        return view('payroll_period', compact(
            'periods', 'gross', 'net', 'deductions', 'employees', 'netTrend',
            'daysToCutoff', 'years', 'currentPeriodName',
            'salaryGrades', 'allEmployees', 'benefits', 'departments'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payout_date' => 'required|date|after_or_equal:end_date'
        ]);

        $overlap = DB::table('payroll_periods')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            })->exists();

        if ($overlap) {
            return redirect('/payroll-period')->with('error', 'Payroll period overlaps with an existing one.');
        }

        $newId = DB::table('payroll_periods')->insertGetId([
            'name' => $request->name,
            'payroll_code' => str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'payout_date' => $request->payout_date,
            'status' => 'pending'
        ]);

        $this->generatePayrollRecords($newId);

        return redirect('/payroll-period')->with('success', 'Payroll period created with employee records.');
    }

    public function update(Request $request, $id)
    {
        $period = DB::table('payroll_periods')->where('id', $id)->first();
        if (!$period) return redirect('/payroll-period')->with('error', 'Payroll period not found.');
        if ($period->status != 'draft') return redirect('/payroll-period')->with('error', 'Only draft payroll periods can be edited.');

        $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payout_date' => 'required|date|after_or_equal:end_date',
            'status' => 'required|in:draft,open,submitted,approved,closed'
        ]);

        $overlap = DB::table('payroll_periods')
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            })->exists();

        if ($overlap) {
            return redirect('/payroll-period')->with('error', 'Payroll period overlaps with an existing one.');
        }

        DB::table('payroll_periods')->where('id', $id)->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'payout_date' => $request->payout_date,
            'status' => $request->status
        ]);

        return redirect('/payroll-period')->with('success', 'Payroll period updated successfully.');
    }

    public function changeStatus($id)
    {
        $period = DB::table('payroll_periods')->where('id', $id)->first();
        if (!$period) return redirect('/payroll-period')->with('error', 'Payroll period not found.');
        if ($period->status == 'completed') return redirect('/payroll-period')->with('error', 'Already completed.');

        DB::table('payroll_periods')->where('id', $id)->update(['status' => 'completed']);

        $start = Carbon::parse($period->start_date);
        $end = Carbon::parse($period->end_date);
        $days = $start->diffInDays($end) + 1;
        $nextStart = $end->copy()->addDay();
        $nextEnd = $nextStart->copy()->addDays($days - 1);

        $exists = DB::table('payroll_periods')->where('start_date', $nextStart->format('Y-m-d'))->exists();

        if (!$exists) {
            $newId = DB::table('payroll_periods')->insertGetId([
                'name' => 'Payroll ' . $nextStart->format('M j') . ' - ' . $nextEnd->format('M j'),
                'payroll_code' => str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT),
                'start_date' => $nextStart,
                'end_date' => $nextEnd,
                'payout_date' => $nextEnd->copy()->addDays(5),
                'status' => 'pending'
            ]);
            $this->generatePayrollRecords($newId);
        }

        return redirect('/payroll-period')->with('success', 'Payroll completed and next period created.');
    }

    private function generatePayrollRecords($periodId)
{
    $period = DB::table('payroll_periods')->where('id', $periodId)->first();
    if (!$period) return;

    $start = Carbon::parse($period->start_date);
    $end = Carbon::parse($period->end_date);
    $days = $start->diffInDays($end) + 1;

    $employees = DB::table('employees')->get();

    foreach ($employees as $employee) {
        // Skip if this employee already has a payslip for this period
        $exists = DB::table('payslips')
            ->where('payroll_period_id', $periodId)
            ->where('employee_id', $employee->employee_id)
            ->exists();
        if ($exists) continue;

        // --- BASIC SALARY & RATES ---
        $salaryGrade = DB::table('salary_grades')->where('id', $employee->salary_grade_id)->first();
        $monthlySalary = $salaryGrade ? $salaryGrade->monthly_basic_salary : 0; 
        $dailyRate = $monthlySalary / 30;
        $hourlyRate = $dailyRate / 8;
        $minuteRate = $hourlyRate / 60;
        $grossPay = $monthlySalary / 2;

        // --- ATTENDANCE LOGS ---
        $attendanceLogs = DB::table('attendance_logs')
            ->where('employee_id', $employee->employee_id)
            ->whereBetween('attendance_date', [$period->start_date, $period->end_date])
            ->get();

        $activeSettings = DB::table('payroll_items')->where('status', 'Active')->get();
        $attendanceEarnings = 0;
        $attendanceDeductions = 0;
        $attendanceItems = [];

        foreach ($activeSettings as $setting) {
            $totalUnits = 0;
            foreach ($attendanceLogs as $log) {
                switch ($setting->category) {
                    case 'late': $totalUnits += $log->late_minutes; break;
                    case 'undertime': $totalUnits += $log->undertime_minutes; break;
                    case 'overtime': $totalUnits += $log->overtime_minutes; break;
                    case 'absent': if ($log->is_absent) $totalUnits += 1; break;
                    case 'holiday': if (isset($log->is_holiday) && $log->is_holiday) $totalUnits += 1; break;
                }
            }

            if ($totalUnits <= 0) continue;

            switch ($setting->basis) {
                case 'per_minute': $amount = $minuteRate * $totalUnits * $setting->multiplier; break;
                case 'per_hour': $amount = $hourlyRate * ($totalUnits / 60) * $setting->multiplier; break;
                case 'per_day': $amount = $dailyRate * $totalUnits * $setting->multiplier; break;
                default: $amount = 0;
            }

            if ($setting->type === 'Addition') $attendanceEarnings += $amount;
            else $attendanceDeductions += $amount;

            $attendanceItems[] = ['type' => $setting->type, 'name' => $setting->name, 'amount' => $amount];
        }

        // --- STATUTORY DEDUCTIONS ---
        $monthlySSS = 0;
        $monthlyPhilHealth = 0;
        $monthlyPagibig = 0;
        $monthlyTax = 0;

        $sssRow = DB::table('sss_contributions')
            ->where('salary_from', '<=', $monthlySalary)
            ->where('salary_to', '>=', $monthlySalary)
            ->first();
        if ($sssRow) $monthlySSS = ($sssRow->employee_share + $sssRow->employer_share) / 2;

        $philhealthRow = DB::table('philhealth_contributions')->first();
        if ($philhealthRow) {
            $salaryPH = max($philhealthRow->min_salary, min($monthlySalary, $philhealthRow->max_salary));
            $monthlyPhilHealth = ($salaryPH * ($philhealthRow->employee_share / 100)) / 2;
        }

        $pagibigRow = DB::table('pagibig_contributions')->first();
        if ($pagibigRow) {
            $salaryCap = min($monthlySalary, $pagibigRow->salary_cap);
            $rate = ($monthlySalary <= $pagibigRow->salary_threshold) ? $pagibigRow->employee_rate_low : $pagibigRow->employee_rate_high;
            $monthlyPagibig = ($salaryCap * ($rate / 100)) / 2;
        }

        $annual = $monthlySalary * 12;
        $taxRow = DB::table('tax_contributions')
            ->where('income_from', '<=', $annual)
            ->where(function ($q) use ($annual) {
                $q->where('income_to', '>=', $annual)->orWhereNull('income_to');
            })->first();
        if ($taxRow && $annual > 250000) {
            $monthlyTax = ($taxRow->base_tax + (($annual - $taxRow->excess_over) * $taxRow->tax_rate)) / 12 / 2;
        }

        $statutoryTotal = $monthlySSS + $monthlyPhilHealth + $monthlyPagibig + $monthlyTax;

// ================= BENEFITS ENGINE ================= //

$benefits = DB::table('benefits')->where('status', 'active')->get();

$benefitsTotal = 0;

foreach ($benefits as $benefit) {

    $eligible = false;

    if ($benefit->eligibility == 'all') {
        $eligible = true;
    }

    elseif ($benefit->eligibility == 'departments') {
        $deptIds = json_decode($benefit->eligible_departments, true);

        if (!empty($deptIds) && in_array((string)$employee->department_id, $deptIds)) {
            $eligible = true;
        }
    }

    elseif ($benefit->eligibility == 'salary_grades') {
        $gradeIds = json_decode($benefit->eligible_salary_grades, true);

        if (!empty($gradeIds) && in_array((string)$employee->salary_grade_id, $gradeIds)) {
            $eligible = true;
        }
    }

    if (!$eligible) continue;

    // FREQUENCY
    $apply = false;

$periodStart = Carbon::parse($period->start_date);
$periodEnd = Carbon::parse($period->end_date);

switch ($benefit->frequency) {

    // ALWAYS ON EVERY PAYROLL CUT-OFF
    case 'per_cutoff':
        $apply = true;
        break;

    // ONLY ON LAST CUT-OFF OF THE MONTH
    case 'monthly':
        $apply = $periodEnd->isSameDay($periodStart->copy()->endOfMonth());
        break;

    // ONLY ON LAST CUT-OFF OF QUARTER
    case 'quarterly':
        $apply = $periodEnd->isSameDay(
            $periodStart->copy()->endOfQuarter()
        );
        break;

    // ONLY DECEMBER PERIOD END
    case 'yearly':
        $apply = $periodEnd->month == 12 && 
                 $periodEnd->isSameDay($periodStart->copy()->endOfYear());
        break;

    default:
        $apply = false;
}


    if (!$apply) continue;

    // ✅ FIXED AMOUNT ONLY
    $amount = $benefit->amount;

    $benefitsTotal += $amount;
}

// ================= FINAL COMPUTATION ================= //

$totalEarnings = $attendanceEarnings; // ONLY attendance
$totalDeductions = $attendanceDeductions + $statutoryTotal;
$netPay = $grossPay
        + $attendanceEarnings
        + $benefitsTotal
        - $attendanceDeductions
        - $statutoryTotal;

        // --- INSERT INTO PAYSLIPS ---
        $payrollRecordId = DB::table('payslips')->insertGetId([
            'payroll_period_id' => $periodId,
            'employee_id' => $employee->employee_id,
            'basic_pay' => $monthlySalary,
            'gross_pay' => $grossPay,
            'benefits_total' => $benefitsTotal,
            'sss' => $monthlySSS,
            'philhealth' => $monthlyPhilHealth,
            'pagibig' => $monthlyPagibig,
            'withholding_tax' => $monthlyTax,
            'total_deductions' => $totalDeductions,
            'other_benefits' => 0,
            'other_deductions' => 0,
            'net_pay' => $netPay,
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // --- INSERT ATTENDANCE ITEMS ---
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

    }
}

    public function viewPage($id)
{
    // Get payroll period
    $period = DB::table('payroll_periods')->where('id', $id)->first();
    if (!$period) {
        return redirect('/payroll-period')->with('error', 'Payroll period not found.');
    }

    // Get payslip records with employee info
    $records = DB::table('payslips')
        ->join('employees', 'payslips.employee_id', '=', 'employees.employee_id')
        ->select('payslips.*', 'employees.fname', 'employees.lname')
        ->where('payroll_period_id', $id)
        ->get();

    // Calculate sums for submitted records
    $gross = $records->where('status', 'Submitted')->sum('gross_pay');
    $deductions = $records->where('status', 'Submitted')->sum('total_deductions');
    $net = $records->where('status', 'Submitted')->sum('net_pay');

    // Counts
    $totalCount = $records->count();
    $submittedCount = $records->where('status', 'Submitted')->count();
    $approvalCount = in_array($period->status, ['submitted', 'approved', 'completed']) ? 1 : 0;
    $financeCount = in_array($period->status, ['approved', 'completed']) ? 1 : 0;
    $disbursementCount = $period->status == 'completed' ? 1 : 0;

    return view('payroll_period_views', compact(
        'period', 'records', 'gross', 'deductions', 'net',
        'totalCount', 'submittedCount', 'approvalCount', 'financeCount', 'disbursementCount'
    ));
}

    public function getEmployeePayroll($id)
{
    // Fetch the payslip with employee info
    $record = DB::table('payslips')
        ->join('employees', 'payslips.employee_id', '=', 'employees.employee_id')
        ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
        ->leftJoin('job_titles', 'employees.job_title_id', '=', 'job_titles.id')
        ->where('payslips.id', $id)
        ->select(
            'payslips.*',
            'employees.fname',
            'employees.lname',
            'departments.name as department_name',
            'job_titles.title as job_title'
        )
        ->first();

    if (!$record) {
        return response()->json(['message' => 'Payslip not found'], 404);
    }

    // Fetch payroll items (attendance-based earnings/deductions)
    $items = DB::table('payroll_items')
        ->where('payroll_record_id', $id)
        ->get();

// GET BENEFITS AGAIN (same logic as compute, but just for display)
$benefits = DB::table('benefits')->where('status', 'active')->get();

$employee = DB::table('employees')->where('employee_id', $record->employee_id)->first();

$benefitItems = [];

foreach ($benefits as $benefit) {

    $eligible = false;

    if ($benefit->eligibility == 'all') {
        $eligible = true;
    }

    elseif ($benefit->eligibility == 'departments') {
        $deptIds = json_decode($benefit->eligible_departments, true);
        if (!empty($deptIds) && in_array((string)$employee->department_id, $deptIds)) {
            $eligible = true;
        }
    }

    elseif ($benefit->eligibility == 'salary_grades') {
        $gradeIds = json_decode($benefit->eligible_salary_grades, true);
        if (!empty($gradeIds) && in_array((string)$employee->salary_grade_id, $gradeIds)) {
            $eligible = true;
        }
    }

    if (!$eligible) continue;

    // SIMPLE: ALWAYS SHOW (same as per_cutoff)
    $benefitItems[] = [
        'name' => $benefit->name,
        'amount' => $benefit->amount
    ];
}

return response()->json([
    'record' => $record,
    'items' => $items,
    'benefits' => $benefitItems
]);
}

    public function updateEmployeeStatus(Request $request, $id)
    {
        $record = DB::table('payslips')->where('id', $id)->first();
        if ($record->status === 'Pending') {
            $newStatus = 'Submitted';
        } else {
            return response()->json(['error' => 'Already submitted and locked']);
        }

        DB::table('payslips')->where('id', $id)->update(['status' => $newStatus]);

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    public function submitForApproval($id)
    {
        $period = DB::table('payroll_periods')->where('id', $id)->first();
        if (!$period) return response()->json(['error' => 'Not found']);

        DB::table('payroll_periods')->where('id', $id)->update(['status' => 'submitted']);

        return response()->json(['success' => true]);
    }

    public function storeSalaryGrade(Request $request)
{
    $request->validate([
        'grade_code' => 'required|unique:salary_grades,grade_code',
        'level_name' => 'required',
        'monthly_basic_salary' => 'required|numeric'
    ]);

    $id = DB::table('salary_grades')->insertGetId([
        'grade_code' => $request->grade_code,
        'level_name' => $request->level_name,
        'monthly_basic_salary' => $request->monthly_basic_salary,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $employees = json_decode($request->employees, true);

if ($employees) {
    foreach ($employees as $empId) {
        DB::table('employees')
            ->where('employee_id', $empId)
            ->update(['salary_grade_id' => $id]);
    }
}

    return back()->with('success', 'Salary grade added!');
}

public function updateSalaryGrade(Request $request, $id)
{
    $request->validate([
        'grade_code' => 'required|unique:salary_grades,grade_code,' . $id,
        'level_name' => 'required',
        'monthly_basic_salary' => 'required|numeric'
    ]);

    DB::table('salary_grades')->where('id', $id)->update([
        'grade_code' => $request->grade_code,
        'level_name' => $request->level_name,
        'monthly_basic_salary' => $request->monthly_basic_salary,
        'updated_at' => now()
    ]);

    if ($request->employees) {
        $data = json_decode($request->employees, true);

        // ADD / KEEP
        $employees = json_decode($request->employees, true);

if ($employees) {
    foreach ($employees as $empId) {
        DB::table('employees')
            ->where('employee_id', $empId)
            ->update(['salary_grade_id' => $id]);
    }
}

        // REMOVE
        $employees = json_decode($request->employees, true);

// REMOVE all current assignments first
DB::table('employees')
    ->where('salary_grade_id', $id)
    ->update(['salary_grade_id' => null]);

// RE-ASSIGN selected
if ($employees) {
    foreach ($employees as $empId) {
        DB::table('employees')
            ->where('employee_id', $empId)
            ->update(['salary_grade_id' => $id]);
    }
}
    }

    return back()->with('success', 'Salary grade updated!');
}

    public function deleteSalaryGrade($id)
    {
        DB::table('salary_grades')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Salary grade deleted!');
    }


    public function updatepagibig(Request $request)
{
    DB::table('pagibig_contributions')->update([
        'salary_cap' => $request->salary_cap,
        'employee_rate_low' => $request->employee_rate_low,
        'employee_rate_high' => $request->employee_rate_high,
        'salary_threshold' => $request->salary_threshold,
        'updated_at' => now()
    ]);

    return back()->with('success', 'Pag-IBIG updated!');
}

public function updatePhilHealth(Request $request)
{
    $request->validate([
        'min_salary' => 'required|numeric',
        'max_salary' => 'required|numeric',
        'contribution_rate' => 'required|numeric',
        'employee_share' => 'required|numeric',
        'employer_share' => 'required|numeric',
    ]);

    DB::table('philhealth_contributions')->update([
        'min_salary' => $request->min_salary,
        'max_salary' => $request->max_salary,
        'contribution_rate' => $request->contribution_rate,
        'employee_share' => $request->employee_share,
        'employer_share' => $request->employer_share,
        'updated_at' => now()
    ]);

    return back()->with('success', 'PhilHealth settings updated!');
}
 
public function updateTax(Request $request)
{
    $ids = $request->id;

    // Clear existing
    DB::table('tax_contributions')->truncate();

    // Re-insert all rows
    for ($i = 0; $i < count($request->income_from); $i++) {
        DB::table('tax_contributions')->insert([
            'income_from' => $request->income_from[$i],
            'income_to' => $request->income_to[$i] ?: null,
            'base_tax' => $request->base_tax[$i],
            'tax_rate' => $request->tax_rate[$i],
            'excess_over' => $request->excess_over[$i],
        ]);
    }

    return back()->with('success', 'Tax table updated!');
}

public function updateSSS(Request $request)
{
    foreach ($request->id as $index => $id) {
        DB::table('sss_contributions')->updateOrInsert(
            ['id' => $id],
            [
                'salary_from' => $request->salary_from[$index],
                'salary_to' => $request->salary_to[$index],
                'monthly_salary_credit' => $request->monthly_salary_credit[$index],
                'employee_share' => $request->employee_share[$index],
                'employer_share' => $request->employer_share[$index]
            ]
        );
    }

    return back()->with('success', 'SSS updated!');
}

// ================= BENEFITS ================= //

public function storeBenefit(Request $request)
{
    $data = $request->except('_token');

    // SAVE MULTI SELECT AS JSON
$data['eligible_departments'] = $request->eligible_departments
    ? json_encode($request->eligible_departments)
    : null;

$data['eligible_salary_grades'] = $request->eligible_salary_grades
    ? json_encode($request->eligible_salary_grades)
    : null;

    if ($request->id) {
        DB::table('benefits')->where('id', $request->id)->update($data);
    } else {
        DB::table('benefits')->insert([
            ...$data,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    return back()->with('success', 'Benefit saved!');
}

public function deleteBenefit($id)
{
    DB::table('benefits')->where('id', $id)->delete();
    return back()->with('success', 'Benefit deleted!');
}

public function storePayrollItem(Request $request)
{
    DB::table('payroll_items')->updateOrInsert(
        ['id' => $request->id],
        [
            'name' => $request->name,
            'type' => ucfirst(strtolower($request->type)) == 'Addition' ? 'Addition' : 'Deduction',
            'status' => ucfirst(strtolower($request->status)) == 'Active' ? 'Active' : 'Inactive',
            'basis' => $request->basis,
            'multiplier' => $request->multiplier,
            'created_at' => now(),
            'updated_at' => now()
        ]
    );

    return back()->with('success', 'Payroll item saved!');
}

public function deletePayrollItem($id)
{
    DB::table('payroll_items')->where('id', $id)->delete();
    return back()->with('success', 'Deleted!');
}

public function unsubmitEmployeeStatus($id)
{
    $record = DB::table('payslips')->where('id', $id)->first();

    if ($record->status === 'Submitted') {
        DB::table('payslips')->where('id', $id)->update([
            'status' => 'Pending'
        ]);

        return response()->json(['success' => true]);
    }

    return response()->json(['error' => 'Only submitted records can be unsubmitted']);
}

public function getProgress($id)
{
    $records = DB::table('payslips')
        ->where('payroll_period_id', $id)
        ->get();

    $period = DB::table('payroll_periods')->where('id', $id)->first();

    return response()->json([
        'total' => $records->count(),
        'submitted' => $records->where('status', 'Submitted')->count(),
        'approval' => in_array($period->status, ['submitted','approved','completed']) ? 1 : 0,
        'finance' => in_array($period->status, ['approved','completed']) ? 1 : 0,
        'disbursement' => $period->status == 'completed' ? 1 : 0,
    ]);
}

public function updateAdjustments(Request $request, $id)
{
    DB::table('payslips')->where('id', $id)->update([
        'other_benefits' => $request->other_benefits ?? 0,
        'other_deductions' => $request->other_deductions ?? 0,
        'net_pay' => DB::raw("
            gross_pay 
            + {$request->other_benefits}
            - total_deductions 
            - {$request->other_deductions}
        ")
    ]);

    return response()->json(['success' => true]);
}



public function export($id)
{
    $record = DB::table('payslips')
        ->join('employees', 'payslips.employee_id', '=', 'employees.employee_id')
        ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
        ->leftJoin('job_titles', 'employees.job_title_id', '=', 'job_titles.id')
        ->leftJoin('payroll_periods', 'payslips.payroll_period_id', '=', 'payroll_periods.id') // ✅ ADD THIS
        ->where('payslips.id', $id)
        ->select(
            'payslips.*',
            'employees.fname',
            'employees.lname',
            'employees.date_hired',
            'departments.name as department_name',
            'job_titles.title as job_title',
            'payroll_periods.name as period_name',
            'payroll_periods.start_date',
            'payroll_periods.end_date',

            DB::raw('(payslips.gross_pay + payslips.benefits_total + payslips.other_benefits) as total_earnings')
        )
        ->first();

    if (!$record) {
        abort(404, 'Payslip not found');
    }

    // Separate earnings & deductions (your blade expects this)
    $earnings = DB::table('payroll_items')
        ->where('payroll_record_id', $id)
        ->where('type', 'Addition')
        ->get();

    $deductions = DB::table('payroll_items')
        ->where('payroll_record_id', $id)
        ->where('type', 'Deduction')
        ->get();

    return view('partials.payslip_print', compact('record', 'earnings', 'deductions'));
}
}