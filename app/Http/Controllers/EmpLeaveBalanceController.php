<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpLeaveBalance;
use App\Http\Controllers\SysAdminLogsController;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
class EmpLeaveBalanceController extends Controller
{
    public function increasebalance(Request $request)
    {
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
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Leave Balance',
            'log_details' => "Increase {$request->leave_type} leave balance by {$request->days} days."
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'message' => "Leave balance ({$request->leave_type}) increased by {$request->days} days for all employees.",
            'success' => true,
        ]);
    }
    public function increasebalanceemp(Request $request)
    {
        $employeeLeaveBal = EmpLeavebalance::where('soc_reference', $request->soc_reference)
            ->where('deleted', 0)
            ->first();
        $employeeLeaveBal->annual_leave_balance = $request->annual;
        $employeeLeaveBal->sick_leave_balance = $request->sick;
        $employeeLeaveBal->carried_forward_balance = $request->carried;
        $employeeLeaveBal->save();
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Employee Leave Balance',
            'log_details' => "Updated employee {$request->soc_reference} leave balance For Annual Leaves {$request->annual}, Sick Leave {$request->sick} and Carried Forward {$request->carried}."
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
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
        $file = $request->file('balsheet');
        $filename = 'leave_balance_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('/excelsheets', $filename, 'public');
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
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Leave Balance',
            'log_details' => "Imported Excel Sheet {$fullpath} to leave balance"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "Leave balances updated from Excel Sheet and file saved successfully in {$fullpath}.",
        ]);
    }
    public function createbalance($bal = [])
    {
        $fields = [
            'soc_reference',
            'annual_leave_balance',
            'sick_leave_balance',
            'carried_forward_balance'
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $bal[$field];
        }
        EmpLeaveBalance::create($data);

    }
    public function rotatebalance(Request $request)
    {
        DB::statement('CALL process_leaveBalanceRotate()');
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Leave Balance',
            'log_details' => "Annual Leave Balance Rotation"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => 'Leave balances rotated successfully.',
        ]);
    }
}
