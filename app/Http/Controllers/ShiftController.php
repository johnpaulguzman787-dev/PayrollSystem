<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    // Show all shifts
    public function index()
    {
        $shifts = DB::table('shifts')
            ->join('departments', 'shifts.department_id', '=', 'departments.id')
            ->select('shifts.*', 'departments.name as department_name')
            ->orderBy('department_name')
            ->orderBy('start_time')
            ->get();

        $departments = DB::table('departments')->orderBy('name')->get();

        return view('shifts', compact('shifts', 'departments'));
    }

    // Store a new shift
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'department_id' => 'required|integer',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time'
        ]);

        DB::table('shifts')->insert([
            'department_id' => $request->department_id,
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ]);

        return redirect('/shifts')->with('success', 'Shift created successfully.');
    }

    // Delete a shift
    public function delete($id)
    {
        DB::table('shifts')->where('id', $id)->delete();
        return redirect('/shifts')->with('success', 'Shift deleted successfully.');
    }

    // Edit shift form (optional)
    public function edit($id)
    {
        $shift = DB::table('shifts')->where('id', $id)->first();
        $departments = DB::table('departments')->orderBy('name')->get();
        return view('shift_edit', compact('shift','departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'department_id' => 'required|integer',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time'
        ]);

        DB::table('shifts')->where('id',$id)->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ]);

        return redirect('/shifts')->with('success','Shift updated successfully.');
    }
}
