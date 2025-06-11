<?php

namespace App\Http\Controllers;

use App\Models\EmpLeaveBalance;
use App\Models\SysAdminLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;


class EmpLeaveBalanceController extends Controller
{
    public function increasebalance(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:annual,sick',
            'days' => 'required|numeric|min:0.5',
            'adminref' => 'required|string'
        ]);
        $employees = EmpLeavebalance::where('deleted', 0)->get();
        foreach ($employees as $employee) {
            switch ($request->leave_type) {
                case 'annual':
                    $employee->annual_leave_balance += $request->days;
                    break;

                case 'sick':
                    $employee->sick_leave_balance += $request->days;
                    break;
            }

            $employee->save();
        }
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'Leave Balance Update',
            'log_details' => "Increase {$request->leave_type} leave balance by {$request->days} days."
        ]);
        return response()->json([
            'message' => "Leave balance ({$request->leave_type}) increased by {$request->days} days for all employees.",
            'success' => true,
        ]);
    }
    public function increasebalanceemp(Request $request)
    {
        $request->validate([
            'soc_reference' => 'required|string',
            'annual' => 'required|numeric',
            'sick' => 'required|numeric',
            'carried' => 'required|numeric',
            'adminref' => 'required|string'
        ]);
        $employeeLeaveBal = EmpLeavebalance::where('soc_reference', $request->soc_reference)
            ->where('deleted', 0)
            ->first();
        $employeeLeaveBal->annual_leave_balance = $request->annual;
        $employeeLeaveBal->sick_leave_balance = $request->sick;
        $employeeLeaveBal->carried_forward_balance = $request->carried;
        $employeeLeaveBal->save();
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'Leave Balance Update',
            'log_details' => "Updated employee {$request->soc_reference} leave balance For Annual Leaves {$request->annual}, Sick Leave {$request->sick} and Carried Forward {$request->carried}."
        ]);
        return response()->json([
            'message' => "Employee {$request->soc_reference} Leave balance updated successfully.",
            'success' => true,
        ]);
    }
    public function show(Request $request)
    {
        $employeeLeaveBal = EmpLeavebalance::where('soc_reference', $request->soc_reference)
            ->where('deleted', 0)
            ->first();
        return response()->json($employeeLeaveBal);
    }
    public function importbalance(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:20480',
            'adminref' => 'required|string'
        ]);
        $file = $request->file('file');
        $filename = 'leave_balance_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('/excelsheets', $filename);
        $fullpath = asset(Storage::url($path));
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        unset($rows[0]);
        foreach ($rows as $row) {
            $reference = trim($row[0]);
            $annual = (float) $row[1];
            $sick = (float) $row[2];
            $carried = (float) $row[3];

            EmpLeaveBalance::updateOrCreate(
                ['soc_reference' => $reference],
                [
                    'annual_leave_balance' => $annual,
                    'sick_leave_balance' => $sick,
                    'carried_forward_balance' => $carried,
                ]
            );
        }
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'Leave Balance Update',
            'log_details' => "Imported Excel Sheet {$fullpath} to leave balance"
        ]);
        return response()->json([
            'success' => true,
            'message' => "Leave balances updated from Excel Sheet and file saved successfully in {$fullpath}.",
        ]);
    }
}
