<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PayrollOfficerGovPay extends Controller
{
    public function index(Request $request)
    {
        $employeeId = session('employee_id');
        $year = $request->year ?? date('Y');
        $years = DB::table('payroll_periods')->selectRaw('YEAR(created_at) as year')->whereNotNull('created_at')->distinct()->orderByDesc('year')->pluck('year');
        $isView = $request->view ?? null;

        if ($isView) {
            $records = DB::table('payslips as ps')
            ->leftJoin('employees as e', 'ps.employee_id', '=', 'e.employee_id')
            ->leftJoin('payroll_periods as pp', 'ps.payroll_period_id', '=', 'pp.id')
            ->where('ps.payroll_period_id', $isView)
            ->where('ps.status', 'Submitted')
            ->whereYear('pp.created_at', $year)
            ->select(
                'ps.id',
                'ps.employee_id',
                'e.fname',
                'e.lname',
                'pp.name as period_name',

                DB::raw("ps.sss as sss"),
                DB::raw("ps.philhealth as philhealth"),
                DB::raw("ps.pagibig as pagibig"),
                DB::raw("ps.withholding_tax as tax"),

                'ps.status'
            )
            ->get();
            $periodName = $records->first()->period_name ?? 'Payroll';
            return view('payroll_officer.payroll-officer_govpay', compact('records', 'periodName', 'years', 'year', 'isView'));
        }

        $contributions = DB::table('payslips as ps')
        ->join('payroll_periods as pp', 'ps.payroll_period_id', '=', 'pp.id')
        ->where('ps.status', 'Submitted')
        ->where('pp.status', 'for_approval')
        ->whereYear('pp.created_at', $year)
        ->select(
            'ps.payroll_period_id',
            'pp.name as period_name',
            'ps.status',

            DB::raw("SUM(ps.sss) as sss_total"),
            DB::raw("SUM(ps.philhealth) as philhealth_total"),
            DB::raw("SUM(ps.pagibig) as pagibig_total"),
            DB::raw("SUM(ps.withholding_tax) as tax_total")
        )
        ->groupBy('ps.payroll_period_id','pp.name','ps.status')
        ->orderByDesc('ps.payroll_period_id')
        ->get();

        $myContributions = collect();
        if ($employeeId) {
            $myContributions = DB::table('payslips as ps')
            ->join('payroll_items as pi', 'ps.id', '=', 'pi.payroll_record_id')
            ->join('payroll_periods as pp', 'ps.payroll_period_id', '=', 'pp.id')
            ->where('ps.status', 'Submitted')
            ->where('pp.status', 'for_approval')
            ->where('ps.employee_id', $employeeId)
            ->whereYear('pp.created_at', $year)
            ->whereIn('pi.name', ['SSS','PhilHealth','Pag-IBIG','Withholding Tax'])
            ->select(
                'ps.payroll_period_id', 'pp.name as period_name', 'ps.status',
                DB::raw("SUM(CASE WHEN pi.name='SSS' THEN pi.amount ELSE 0 END) as sss_total"),
                DB::raw("SUM(CASE WHEN pi.name='PhilHealth' THEN pi.amount ELSE 0 END) as philhealth_total"),
                DB::raw("SUM(CASE WHEN pi.name='Pag-IBIG' THEN pi.amount ELSE 0 END) as pagibig_total"),
                DB::raw("SUM(CASE WHEN pi.name='Withholding Tax' THEN pi.amount ELSE 0 END) as tax_total")
            )
            ->groupBy('ps.payroll_period_id','pp.name','ps.status')
            ->orderByDesc('ps.payroll_period_id')
            ->get();
        }

        return view('payroll_officer.payroll-officer_govpay', compact('contributions', 'myContributions', 'years', 'year', 'isView'));
    }
}