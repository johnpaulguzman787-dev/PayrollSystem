<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // List employees and departments
    public function index()
    {
        $employees = DB::table('employees')
            ->join('departments','employees.department_id','=','departments.id')
            ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
            ->select('employees.*','departments.name as department_name','job_titles.title as job_title')
            ->orderByDesc('employee_id')
            ->get();

        $departments = DB::table('departments')->orderBy('name')->get();
        $job_titles = DB::table('job_titles')->orderBy('title')->get();

        return view('employee', [
            'employees' => $employees,
            'departments' => $departments,
            'job_titles' => $job_titles
        ]);
    }

    // Store new employee
public function store(Request $request)
{
    $request->validate([
        'fname'=>'required',
        'lname'=>'required',
        'email'=>'required|email|unique:employees,email',
        'gender'=>'required|in:Male,Female',
        'date_of_birth'=>'required|date',
        'date_hired'=>'required|date',
        'department_id'=>'required|integer',
        'job_title_id'=>'required|integer',
        'basic_salary'=>'required|numeric|min:0'
    ]);

    // 🔥 Generate Employee ID
    $employee_id = strtoupper(substr(str_shuffle('0123456789'), 0, 6));

    while(DB::table('employees')->where('employee_id', $employee_id)->exists()){
        $employee_id = strtoupper(substr(str_shuffle('0123456789'), 0, 6));
    }

    // 🔥 Generate RANDOM PASSWORD (8 chars)
    $plainPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

    // 🔥 HASH password
    $hashedPassword = Hash::make($plainPassword);

    // ✅ Save to employees
    DB::table('employees')->insert([
        'employee_id'=>$employee_id,
        'fname'=>$request->fname,
        'mname'=>$request->mname,
        'lname'=>$request->lname,
        'email'=>$request->email,
        'gender'=>$request->gender,
        'date_of_birth'=>$request->date_of_birth,
        'date_hired'=>$request->date_hired,
        'department_id'=>$request->department_id,
        'job_title_id'=>$request->job_title_id,
        'basic_salary'=>$request->basic_salary,
        'contact_no'=>$request->contact_no,
        'address'=>$request->address,
        'created_at'=>now(),
        'updated_at'=>now()
    ]);

    // ✅ Save to users table
    DB::table('users')->insert([
        'email'=>$request->email,
        'password'=>$hashedPassword,
        'created_at'=>now(),
        'updated_at'=>now()
    ]);

    return redirect('/employees')
    ->with('success', 'Employee added successfully!')
    ->with('generated_password', $plainPassword)
    ->with('employee_id', $employee_id);
}

    // Update employee
public function update(Request $request, $id)
{
    $request->validate([
        'fname'=>'required',
        'lname'=>'required',
        'email'=>'required|email|unique:employees,email,'.$id.',employee_id',
        'gender'=>'required|in:Male,Female',
        'date_of_birth'=>'required|date',
        'date_hired'=>'required|date',
        'department_id'=>'required|integer',
        'job_title_id'=>'required|integer',
        'basic_salary'=>'required|numeric|min:0',
        'contact_no'=>'nullable',
        'address'=>'nullable'
    ]);

    // 🔥 Get OLD email before update
    $oldEmail = DB::table('employees')
        ->where('employee_id', $id)
        ->value('email');

    // ✅ Update employees table
    DB::table('employees')->where('employee_id',$id)->update([
        'fname'=>$request->fname,
        'mname'=>$request->mname,
        'lname'=>$request->lname,
        'email'=>$request->email, // 🔥 ADD THIS
        'gender'=>$request->gender,
        'date_of_birth'=>$request->date_of_birth,
        'date_hired'=>$request->date_hired,
        'department_id'=>$request->department_id,
        'job_title_id'=>$request->job_title_id,
        'basic_salary'=>$request->basic_salary,
        'contact_no'=>$request->contact_no,
        'address'=>$request->address,
        'updated_at'=>now()
    ]);

    // ✅ Update users table (SYNC EMAIL)
    DB::table('users')
        ->where('email', $oldEmail)
        ->update([
            'email' => $request->email,
            'updated_at' => now()
        ]);

    return redirect('/employees')
        ->with('success','Employee updated successfully.');
}


    // Delete employee
    public function delete($id)
    {
        DB::table('employees')->where('employee_id',$id)->delete();
        return redirect('/employees')->with('success','Employee deleted successfully.');
    }

    // Store new department
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:departments,name'
        ]);

        DB::table('departments')->insert([
            'name'=>$request->name,
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        return redirect('/employees')->with('success','Department added successfully.');
    }

    // Delete department
    public function deleteDepartment($id)
    {
        // Optional: Check if any employee is assigned to this department
        $count = DB::table('employees')->where('department_id', $id)->count();
        if($count > 0){
            return redirect('/employees')->with('error','Cannot delete department. Employees are assigned to it.');
        }

        DB::table('departments')->where('id',$id)->delete();
        return redirect('/employees')->with('success','Department deleted successfully.');
    }

    // Store Job Title
public function storeJobTitle(Request $request)
{
    $request->validate([
        'title'=>'required',
        'department_id'=>'required|integer'
    ]);

    DB::table('job_titles')->insert([
        'title'=>$request->title,
        'department_id'=>$request->department_id,
        'created_at'=>now(),
        'updated_at'=>now()
    ]);

    return redirect('/employees')->with('success','Job title added successfully.');
}

// Delete Job Title
public function deleteJobTitle($id)
{
    $count = DB::table('employees')->where('job_title_id',$id)->count();

    if($count > 0){
        return redirect('/employees')->with('error','Cannot delete job title. Employees assigned to it.');
    }

    DB::table('job_titles')->where('id',$id)->delete();

    return redirect('/employees')->with('success','Job title deleted successfully.');
}
}
