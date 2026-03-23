<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollItemSettingsController extends Controller
{

    // SHOW SETTINGS PAGE
    public function index()
    {
        $items = DB::table('payroll_items_settings')
            ->orderBy('id')
            ->get();

        return view('payroll_items_settings', compact('items'));
    }


    // UPDATE MULTIPLIER
    public function update(Request $request, $id)
    {

        DB::table('payroll_items_settings')
        ->where('id', $id)
        ->update([

            'multiplier' => $request->multiplier,
            'is_paid' => $request->is_paid,
            'is_active' => $request->is_active,
            'updated_at' => now()

        ]);

        return back()->with('success','Payroll item updated successfully');

    }

    public function updateMultiplier(Request $request, $id)
{
    $request->validate([
        'multiplier' => 'required|numeric|min:0',
    ]);

    DB::table('payroll_items_settings')
        ->where('id', $id)
        ->update([
            'multiplier' => $request->multiplier,
            'updated_at' => now()
        ]);

    return back()->with('success','Multiplier updated successfully.');
}
}