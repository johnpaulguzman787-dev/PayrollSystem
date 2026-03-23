<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayslipController extends Controller
{

    public function index(Request $request)
    {
        $periodFilter = $request->period;
        $departmentFilter = $request->department;

            $query = DB::table('payroll_records')
            ->join('employees','payroll_records.employee_id','=','employees.employee_id')
            ->join('departments','employees.department_id','=','departments.id')
            ->join('payroll_periods','payroll_records.payroll_period_id','=','payroll_periods.id')
            ->select(
                'payroll_records.*',  // payroll_code is here if stored in payroll_records
                'employees.fname',
                'employees.lname',
                'departments.name as department_name',
                'payroll_periods.name as period_name',
                'payroll_periods.start_date',
                'payroll_periods.end_date',
                'payroll_periods.status as period_status',
                'payroll_records.payroll_code' // ✅ Add this line
            )
            ->orderByDesc('payroll_records.id');

        // 🔹 FILTER IF SPECIFIC PERIOD SELECTED
        if ($periodFilter && $periodFilter != 'all') {
            $query->where('payroll_records.payroll_period_id', $periodFilter);
        }
        if ($departmentFilter && $departmentFilter != 'all') {
            $query->where('employees.department_id', $departmentFilter);
        }
        $statusFilter = $request->status;

        if ($statusFilter && $statusFilter != 'all') {
            $query->where('payroll_records.status', $statusFilter);
        }

        $records = $query->get();

        foreach ($records as $record) {

            $record->earnings = DB::table('payroll_items')
                ->where('payroll_record_id', $record->id)
                ->where('type','earning')
                ->get();

            $record->deductions = DB::table('payroll_items')
                ->where('payroll_record_id', $record->id)
                ->where('type','deduction')
                ->whereNotIn('name', [
                    'SSS','PhilHealth','Pag-IBIG','Withholding Tax'
                ])
                ->get();

            $record->statutory = DB::table('payroll_items')
                ->where('payroll_record_id', $record->id)
                ->where('type','deduction')
                ->whereIn('name', [
                    'SSS','PhilHealth','Pag-IBIG','Withholding Tax'
                ])
                ->get();
        }

        $periods = DB::table('payroll_periods')->get();
        $departments = DB::table('departments')->get();

        return view('payslip', compact(
            'records',
            'periods',
            'departments',
            'periodFilter',
            'departmentFilter',
            'statusFilter'
        ));
    }

   // 🔥 UPDATE PAYROLL RECORD + ITEMS
public function update(Request $request, $id)
{
    $record = DB::table('payroll_records')->where('id',$id)->first();

    $period = DB::table('payroll_periods')
        ->where('id', $record->payroll_period_id)
        ->first();

    if($period->status == 'closed'){
        return back()->with('error','This payroll period is already closed and cannot be edited.');
    }
    
    DB::transaction(function () use ($request, $id) {

        DB::table('payroll_items')
            ->where('payroll_record_id',$id)
            ->whereNotIn('name', [
                'SSS',
                'PhilHealth',
                'Pag-IBIG',
                'Withholding Tax'
            ])
            ->delete();

        $totalEarnings = 0;
        $totalDeductions = 0;

        /* GET EXISTING STATUTORY */
        $statutoryTotal = DB::table('payroll_items')
            ->where('payroll_record_id',$id)
            ->whereIn('name', [
                'SSS',
                'PhilHealth',
                'Pag-IBIG',
                'Withholding Tax'
            ])
            ->sum('amount');

        $totalDeductions += $statutoryTotal;

        foreach ($request->earnings ?? [] as $item) {

            DB::table('payroll_items')->insert([
                'payroll_record_id' => $id,
                'type' => 'earning',
                'name' => $item['name'],
                'amount' => $item['amount'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $totalEarnings += $item['amount'];
        }

        foreach ($request->deductions ?? [] as $item) {

            DB::table('payroll_items')->insert([
                'payroll_record_id' => $id,
                'type' => 'deduction',
                'name' => $item['name'],
                'amount' => $item['amount'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $totalDeductions += $item['amount'];
        }

        $record = DB::table('payroll_records')->where('id',$id)->first();

        $netPay = $record->gross_pay + $totalEarnings - $totalDeductions;

        DB::table('payroll_records')
        ->where('id',$id)
        ->update([
            'payroll_period_id' => $request->payroll_period_id,
            'total_earnings' => $totalEarnings,
            'total_deductions' => $totalDeductions,
            'net_pay' => $netPay,
            'status' => $request->status,
            'updated_at' => now()
        ]);
    });

    /*
    =====================================
    AUTO CLOSE PAYROLL PERIOD
    IF ALL RECORDS ARE PAID
    =====================================
    */

    $periodId = $request->payroll_period_id;

    $remaining = DB::table('payroll_records')
        ->where('payroll_period_id', $periodId)
        ->where('status', '!=', 'paid')
        ->count();

    if ($remaining == 0) {

        DB::table('payroll_periods')
            ->where('id', $periodId)
            ->update([
                'status' => 'closed'
            ]);
    }

    return back()->with('success','Payroll updated successfully');
}



    // 🔥 EXPORT SINGLE PAYSLIP
    public function export($id)
    {
        $record = DB::table('payroll_records')
            ->join('employees','payroll_records.employee_id','=','employees.employee_id')
            ->join('payroll_periods','payroll_records.payroll_period_id','=','payroll_periods.id')
            ->leftJoin('departments','employees.department_id','=','departments.id')
            ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
            ->select(
                'payroll_records.*',
                'employees.fname',
                'employees.lname',
                'employees.basic_salary',
                'employees.date_hired', // ✅ ADD THIS
                'departments.name as department_name', // ✅ ADD THIS
                'job_titles.title as job_title_name', // ✅ ADD THIS
                'payroll_periods.name as period_name',
                'payroll_periods.start_date',
                'payroll_periods.end_date'
            )
            ->where('payroll_records.id',$id)
            ->first();

        $earnings = DB::table('payroll_items')
            ->where('payroll_record_id',$id)
            ->where('type','earning')
            ->get();

        $deductions = DB::table('payroll_items')
            ->where('payroll_record_id',$id)
            ->where('type','deduction')
            ->get();

        return view('partials.payslip_print', compact(
            'record',
            'earnings',
            'deductions'
        ));
    }

    // 🔥 EXPORT ENTIRE PAYROLL PERIOD
    public function exportPeriod($periodId)
    {
        $records = DB::table('payroll_records')
            ->join('employees','payroll_records.employee_id','=','employees.employee_id')
            ->join('payroll_periods','payroll_records.payroll_period_id','=','payroll_periods.id')
            ->select(
                'payroll_records.*',
                'employees.fname',
                'employees.lname',
                'employees.basic_salary',
                'payroll_periods.name as period_name',
                'payroll_periods.start_date',
                'payroll_periods.end_date'
            )
            ->where('payroll_records.payroll_period_id',$periodId)
            ->get();

        return view('partials.period_export', compact('records'));
    }

        public function destroy($id)
        {
            $record = DB::table('payroll_records')->where('id',$id)->first();

            if($record->status == 'finalized' || $record->status == 'paid'){
                return back()->with('error','Finalized or Paid payroll cannot be deleted.');
            }

            DB::transaction(function() use ($id) {
                DB::table('payroll_items')->where('payroll_record_id', $id)->delete();
                DB::table('payroll_records')->where('id', $id)->delete();
            });

            return back()->with('success', 'Payslip deleted successfully');
        }

    public function bulkAction(Request $request)
{
    $ids = $request->ids;
    $action = $request->action;

    if(!$ids){
        return response()->json(['success'=>false]);
    }

    foreach($ids as $id){

        $record = DB::table('payroll_records')->where('id',$id)->first();

        if(!$record){
            continue;
        }

        $current = $record->status;

        // BLOCK IF PAID
        if($current == 'paid'){
            continue;
        }

        // PREVENT DOWNGRADE
        $flow = [
            'draft' => 1,
            'review' => 2,
            'approval' => 3,
            'paid' => 4
        ];

        if($flow[$action] < $flow[$current]){
            continue;
        }

        if($action == 'delete'){

            DB::table('payroll_items')
                ->where('payroll_record_id',$id)
                ->delete();

            DB::table('payroll_records')
                ->where('id',$id)
                ->delete();

        }else{

            DB::table('payroll_records')
                ->where('id',$id)
                ->update([
                    'status' => $action,
                    'updated_at' => now()
                ]);
        }
    }

    // 🔹 AUTO CLOSE PAYROLL PERIOD(S) IF ALL RECORDS ARE PAID
    $periodIds = DB::table('payroll_records')
        ->whereIn('id', $ids)
        ->pluck('payroll_period_id')
        ->unique();

    foreach($periodIds as $periodId){

        $remaining = DB::table('payroll_records')
            ->where('payroll_period_id', $periodId)
            ->where('status', '!=', 'paid')
            ->count();

        if($remaining == 0){

            DB::table('payroll_periods')
                ->where('id', $periodId)
                ->update([
                    'status' => 'closed'
                ]);
        }
    }

    return response()->json(['success'=>true]);
}
}