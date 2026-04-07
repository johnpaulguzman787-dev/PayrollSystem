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
            $records = DB::table('payroll_records as pr')
                ->leftJoin('employees as e', 'pr.employee_id', '=', 'e.employee_id')
                ->leftJoin('payroll_items as pi', 'pr.id', '=', 'pi.payroll_record_id')
                ->leftJoin('payroll_periods as pp', 'pr.payroll_period_id', '=', 'pp.id')
                ->where('pr.payroll_period_id', $isView)
                ->where('pr.status', 'submitted')
                ->whereYear('pp.created_at', $year)
                ->select(
                    'pr.id', 'pr.employee_id', 'e.fname', 'e.lname', 'pp.name as period_name',
                    DB::raw("SUM(CASE WHEN pi.name = 'SSS' THEN pi.amount ELSE 0 END) as sss"),
                    DB::raw("SUM(CASE WHEN pi.name = 'PhilHealth' THEN pi.amount ELSE 0 END) as philhealth"),
                    DB::raw("SUM(CASE WHEN pi.name = 'Pag-IBIG' THEN pi.amount ELSE 0 END) as pagibig"),
                    DB::raw("SUM(CASE WHEN pi.name = 'Withholding Tax' THEN pi.amount ELSE 0 END) as tax"),
                    'pr.status'
                )
                ->groupBy('pr.id','pr.employee_id','e.fname','e.lname','pp.name','pr.status')
                ->get();
            $periodName = $records->first()->period_name ?? 'Payroll';
            return view('payroll_officer.payroll-officer_govpay', compact('records', 'periodName', 'years', 'year', 'isView'));
        }

        $contributions = DB::table('payroll_records as pr')
            ->join('payroll_items as pi', 'pr.id', '=', 'pi.payroll_record_id')
            ->join('payroll_periods as pp', 'pr.payroll_period_id', '=', 'pp.id')
            ->where('pr.status', 'submitted')->where('pp.status', 'for_approval')->whereYear('pp.created_at', $year)
            ->whereIn('pi.name', ['SSS','PhilHealth','Pag-IBIG','Withholding Tax'])
            ->select(
                'pr.payroll_period_id', 'pp.name as period_name', 'pr.status',
                DB::raw("SUM(CASE WHEN pi.name='SSS' THEN pi.amount ELSE 0 END) as sss_total"),
                DB::raw("SUM(CASE WHEN pi.name='PhilHealth' THEN pi.amount ELSE 0 END) as philhealth_total"),
                DB::raw("SUM(CASE WHEN pi.name='Pag-IBIG' THEN pi.amount ELSE 0 END) as pagibig_total"),
                DB::raw("SUM(CASE WHEN pi.name='Withholding Tax' THEN pi.amount ELSE 0 END) as tax_total")
            )
            ->groupBy('pr.payroll_period_id','pp.name','pr.status')->orderByDesc('pr.payroll_period_id')->get();

        $myContributions = collect();
        if ($employeeId) {
            $myContributions = DB::table('payroll_records as pr')
                ->join('payroll_items as pi', 'pr.id', '=', 'pi.payroll_record_id')
                ->join('payroll_periods as pp', 'pr.payroll_period_id', '=', 'pp.id')
                ->where('pr.status', 'submitted')->where('pp.status', 'for_approval')->where('pr.employee_id', $employeeId)
                ->whereYear('pp.created_at', $year)->whereIn('pi.name', ['SSS','PhilHealth','Pag-IBIG','Withholding Tax'])
                ->select(
                    'pr.payroll_period_id', 'pp.name as period_name', 'pr.status',
                    DB::raw("SUM(CASE WHEN pi.name='SSS' THEN pi.amount ELSE 0 END) as sss_total"),
                    DB::raw("SUM(CASE WHEN pi.name='PhilHealth' THEN pi.amount ELSE 0 END) as philhealth_total"),
                    DB::raw("SUM(CASE WHEN pi.name='Pag-IBIG' THEN pi.amount ELSE 0 END) as pagibig_total"),
                    DB::raw("SUM(CASE WHEN pi.name='Withholding Tax' THEN pi.amount ELSE 0 END) as tax_total")
                )
                ->groupBy('pr.payroll_period_id','pp.name','pr.status')->orderByDesc('pr.payroll_period_id')->get();
        }

        return view('payroll_officer.payroll-officer_govpay', compact('contributions', 'myContributions', 'years', 'year', 'isView'));
    }
}