<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GovernmentContributionController extends Controller
{

    /* ======================================================
       LOAD PAGE
    ====================================================== */

    public function index()
{
    $sss = DB::table('sss_contributions')->orderBy('salary_from')->get();
    $tax = DB::table('tax_contributions')->orderBy('income_from')->get();
    $philhealth = DB::table('philhealth_contributions')->get();
    $pagibig = DB::table('pagibig_contributions')->get();

    // ==========================
    // EMPLOYEE DATA
    // ==========================

    $employees = DB::table('employees')->get();
    $totalEmployees = $employees->count();

    $totalSSSEmployee = 0;
    $totalSSSEmployer = 0;

    foreach ($employees as $emp) {

        $sssRow = DB::table('sss_contributions')
            ->where('salary_from','<=',$emp->basic_salary)
            ->where('salary_to','>=',$emp->basic_salary)
            ->where('status','active')
            ->first();

        if($sssRow){
            $totalSSSEmployee += $sssRow->employee_share;
            $totalSSSEmployer += $sssRow->employer_share;
        }
    }

    // ==========================
    // PHILHEALTH TOTAL
    // ==========================

    $ph = DB::table('philhealth_contributions')
        ->where('status','active')
        ->first();

    $totalPhilhealth = 0;

    if($ph){
        foreach($employees as $emp){

            $salary = $emp->basic_salary;

            if($salary < $ph->min_salary){
                $salary = $ph->min_salary;
            }

            if($salary > $ph->max_salary){
                $salary = $ph->max_salary;
            }

            $totalPhilhealth += ($salary * ($ph->contribution_rate/100));
        }
    }

    return view('govt_item', compact(
        'sss',
        'tax',
        'philhealth',
        'pagibig',
        'totalEmployees',
        'totalSSSEmployee',
        'totalSSSEmployer',
        'totalPhilhealth'
    ));
}


    /* ======================================================
       SSS
    ====================================================== */

    public function storeSSS(Request $request)
    {
        DB::table('sss_contributions')->insert([
            'salary_from' => $request->salary_from,
            'salary_to' => $request->salary_to,
            'monthly_salary_credit' => $request->monthly_salary_credit,
            'employee_share' => $request->employee_share,
            'employer_share' => $request->employer_share,
            'total' => $request->employee_share + $request->employer_share
        ]);

        return redirect()->back()->with('success','SSS Added');
    }


    public function updateSSS(Request $request, $id)
    {
        DB::table('sss_contributions')
        ->where('id',$id)
        ->update([
            'salary_from' => $request->salary_from,
            'salary_to' => $request->salary_to,
            'monthly_salary_credit' => $request->monthly_salary_credit,
            'employee_share' => $request->employee_share,
            'employer_share' => $request->employer_share,
            'total' => $request->employee_share + $request->employer_share
        ]);

        return redirect()->back()->with('success','SSS Updated');
    }


    public function deleteSSS($id)
    {
        DB::table('sss_contributions')->where('id',$id)->delete();

        return redirect()->back()->with('success','SSS Deleted');
    }

    public function toggleSSS($id)
    {
        $row = DB::table('sss_contributions')->where('id',$id)->first();

        $newStatus = $row->status == 'active' ? 'inactive' : 'active';

        DB::table('sss_contributions')
            ->where('id',$id)
            ->update([
                'status'=>$newStatus
            ]);

        return redirect()->back()->with('success','SSS Status Updated');
    }

    /* ======================================================
       TAX
    ====================================================== */

    public function storeTax(Request $request)
    {
        DB::table('tax_contributions')->insert([
            'income_from' => $request->income_from,
            'income_to' => $request->income_to,
            'base_tax' => $request->base_tax,
            'tax_rate' => $request->tax_rate,
            'excess_over' => $request->excess_over
        ]);

        return redirect()->back()->with('success','Tax Added');
    }


    public function updateTax(Request $request,$id)
    {
        DB::table('tax_contributions')
        ->where('id',$id)
        ->update([
            'income_from' => $request->income_from,
            'income_to' => $request->income_to,
            'base_tax' => $request->base_tax,
            'tax_rate' => $request->tax_rate,
            'excess_over' => $request->excess_over
        ]);

        return redirect()->back()->with('success','Tax Updated');
    }


    public function deleteTax($id)
    {
        DB::table('tax_contributions')->where('id',$id)->delete();

        return redirect()->back()->with('success','Tax Deleted');
    }

    public function toggleTax($id)
    {
        $row = DB::table('tax_contributions')->where('id',$id)->first();

        $newStatus = $row->status == 'active' ? 'inactive' : 'active';

        DB::table('tax_contributions')
            ->where('id',$id)
            ->update([
                'status'=>$newStatus
            ]);

        return redirect()->back()->with('success','Tax Status Updated');
    }


    /* ======================================================
       PHILHEALTH
    ====================================================== */

    public function storePhilhealth(Request $request)
    {
        DB::table('philhealth_contributions')->insert([
            'contribution_rate' => $request->contribution_rate,
            'employee_share' => $request->employee_share,
            'employer_share' => $request->employer_share,
            'min_salary' => $request->min_salary,
            'max_salary' => $request->max_salary
        ]);

        return redirect()->back()->with('success','PhilHealth Added');
    }


    public function updatePhilhealth(Request $request,$id)
    {
        DB::table('philhealth_contributions')
        ->where('id',$id)
        ->update([
            'contribution_rate' => $request->contribution_rate,
            'employee_share' => $request->employee_share,
            'employer_share' => $request->employer_share,
            'min_salary' => $request->min_salary,
            'max_salary' => $request->max_salary
        ]);

        return redirect()->back()->with('success','PhilHealth Updated');
    }

    public function togglePhilhealth($id)
    {
        $row = DB::table('philhealth_contributions')->where('id',$id)->first();

        $newStatus = $row->status == 'active' ? 'inactive' : 'active';

        DB::table('philhealth_contributions')
            ->where('id',$id)
            ->update([
                'status'=>$newStatus
            ]);

        return redirect()->back()->with('success','PhilHealth Status Updated');
    }

    /* ======================================================
       PAGIBIG
    ====================================================== */

    public function storePagibig(Request $request)
    {
        DB::table('pagibig_contributions')->insert([
            'salary_cap' => $request->salary_cap,
            'employee_rate_low' => $request->employee_rate_low,
            'employee_rate_high' => $request->employee_rate_high,
            'salary_threshold' => $request->salary_threshold
        ]);

        return redirect()->back()->with('success','Pagibig Added');
    }


    public function updatePagibig(Request $request,$id)
    {
        DB::table('pagibig_contributions')
        ->where('id',$id)
        ->update([
            'salary_cap' => $request->salary_cap,
            'employee_rate_low' => $request->employee_rate_low,
            'employee_rate_high' => $request->employee_rate_high,
            'salary_threshold' => $request->salary_threshold
        ]);

        return redirect()->back()->with('success','Pagibig Updated');
    }

    public function togglePagibig($id)
    {
        $row = DB::table('pagibig_contributions')->where('id',$id)->first();

        $newStatus = $row->status == 'active' ? 'inactive' : 'active';

        DB::table('pagibig_contributions')
            ->where('id',$id)
            ->update([
                'status'=>$newStatus
            ]);

        return redirect()->back()->with('success','Pagibig Status Updated');
    }

    
}