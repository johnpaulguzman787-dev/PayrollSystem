<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceLogController extends Controller
{
    public function index()
    {
        $logs = DB::table('attendance_logs')
            ->join('employees','attendance_logs.employee_id','=','employees.employee_id')
            ->join('shifts','attendance_logs.shift_id','=','shifts.id')
            ->select(
                'attendance_logs.*',
                'employees.fname',
                'employees.lname',
                'shifts.name as shift_name'
            )
            ->orderByDesc('attendance_date')
            ->get();

        $employees = DB::table('employees')->get();
        $shifts = DB::table('shifts')->get();

        return view('attendance_logs', compact('logs','employees','shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'shift_id' => 'required',
            'attendance_date' => 'required|date'
        ]);

        $shift = DB::table('shifts')->where('id',$request->shift_id)->first();

        $late = 0;
        $undertime = 0;
        $overtime = 0;
        $absent = 0;

        if(!$request->time_in || !$request->time_out){
            $absent = 1;
        } else {

            $shiftStart = Carbon::parse($shift->start_time);
            $shiftEnd = Carbon::parse($shift->end_time);
            $timeIn = Carbon::parse($request->time_in);
            $timeOut = Carbon::parse($request->time_out);

            // LATE
            if($timeIn->gt($shiftStart)){
                $late = $shiftStart->diffInMinutes($timeIn);
            }

            // UNDERTIME
            if($timeOut->lt($shiftEnd)){
                $undertime = $timeOut->diffInMinutes($shiftEnd);
            }

            // OVERTIME
            if($timeOut->gt($shiftEnd)){
                $overtime = $shiftEnd->diffInMinutes($timeOut);
            }
        }

        DB::table('attendance_logs')->insert([
            'employee_id' => $request->employee_id,
            'shift_id' => $request->shift_id,
            'attendance_date' => $request->attendance_date,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'late_minutes' => $late,
            'undertime_minutes' => $undertime,
            'overtime_minutes' => $overtime,
            'is_absent' => $absent,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success','Attendance recorded');
    }
}