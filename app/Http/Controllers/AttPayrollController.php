<?php

namespace App\Http\Controllers;

use App\Models\AttPayroll;
use App\Models\AttListPayroll;
use App\Models\AttWBS;
use App\Models\AttSumPayroll;
use App\Models\SysAdminLogs;
use Illuminate\Http\Request;

class AttPayrollController extends Controller
{
    public function list(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'year' => 'required|numeric',
        ]);
        $month = $request->month;
        $year = $request->year;
        $listPayroll = AttListPayroll::where('pay_month', $month)->where('pay_year', $year)->get();
        return response()->json($listPayroll);
    }
    public function show(Request $request)
    {
        $request->validate([
            'reference' => 'required|string'
        ]);
        $listPayroll = AttListPayroll::where('soc_reference', $request->reference)->get();
        return response()->json($listPayroll);
    }
    public function sums(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'year' => 'required|numeric',
        ]);
        $month = $request->month;
        $year = $request->year;
        $sumPayroll = AttSumPayroll::where('pay_month', $month)->where('pay_year', $year)->get();
        return response()->json($sumPayroll);
    }
    public function delete(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'year' => 'required|numeric',
            'adminref' => 'required|string'
        ]);
        $month = $request->month;
        $year = $request->year;
        AttPayroll::where('pay_month', $month)->where('pay_year', $year)->update(['deleted' => 0]);
        AttWBS::where('pay_month', $month)->where('pay_year', $year)->update(['deleted' => 0]);
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'Payroll Sheet',
            'log_details' => "Deleted payroll sheet for  {$month} - {$year}."
        ]);
        return response()->json([
            'message' => "Payroll  sheet for  {$month} - {$year} deleted successfully.",
            'success' => true,
        ]);
    }
}
