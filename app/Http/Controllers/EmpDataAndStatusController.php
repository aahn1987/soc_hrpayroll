<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttPayroll;
use App\Models\EmpGeneralInfo;
use App\Models\SysAdminLogs;
class EmpDataAndStatusController extends Controller
{
    public function statuschanger(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'year' => 'required|numeric',
            'adminref' => 'required|string'
        ]);
        $month = $request->month;
        $year = $request->year;
        EmpGeneralInfo::query()->update(['employee_status' => 0]);
        $references = AttPayroll::where('pay_month', $month)
            ->where('pay_year', $year)
            ->pluck('soc_reference')
            ->toArray();
        EmpGeneralInfo::whereIn('soc_reference', $references)->update(['employee_status' => 1]);
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'IOM Employees',
            'log_details' => "Updated employees statuses updated based on payroll records for {$month} - {$year}"
        ]);
        return response()->json([
            'success' => true,
            'message' => "Employee statuses updated based on payroll records for {$month} - {$year}.",
        ]);
    }
    public function importdata(Request $request)
    {
        //
    }
}
