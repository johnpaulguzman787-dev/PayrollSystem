<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeShiftController extends Controller
{
    // Show employee shifts page
    public function index()
    {
        $employees = DB::table('employees')->orderBy('lname')->get();
        $payroll_periods = DB::table('payroll_periods')->orderByDesc('id')->get();
        $shifts = DB::table('shifts')->orderBy('start_time')->get();
        $employee_shifts = DB::table('employee_shifts')
            ->join('employees','employee_shifts.employee_id','=','employees.employee_id')
            ->join('shifts','employee_shifts.shift_id','=','shifts.id')
            ->join('payroll_periods','employee_shifts.payroll_period_id','=','payroll_periods.id')
            ->select('employee_shifts.*','employees.fname','employees.lname','shifts.name as shift_name','shifts.start_time','shifts.end_time','payroll_periods.start_date as period_start','payroll_periods.end_date as period_end')
            ->orderByDesc('employee_shifts.shift_date')
            ->get();

        return view('employee_shifts', compact('employees','payroll_periods','shifts','employee_shifts'));
    }

    // Store employee shift
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'=>'required',
            'payroll_period_id'=>'required|integer',
            'shift_id'=>'required|integer',
            'shift_date'=>'required|date'
        ]);

        // Check for overlapping shift
        $exists = DB::table('employee_shifts')
            ->where('employee_id', $request->employee_id)
            ->where('shift_date', $request->shift_date)
            ->first();

        if($exists){
            return redirect('/employee-shifts')->with('error','This employee already has a shift on the selected date.');
        }

        DB::table('employee_shifts')->insert([
            'employee_id'=>$request->employee_id,
            'payroll_period_id'=>$request->payroll_period_id,
            'shift_id'=>$request->shift_id,
            'shift_date'=>$request->shift_date
        ]);

        return redirect('/employee-shifts')->with('success','Shift assigned successfully.');
    }

    // Delete employee shift
    public function delete($id)
    {
        DB::table('employee_shifts')->where('id',$id)->delete();
        return redirect('/employee-shifts')->with('success','Shift removed successfully.');
    }
}
