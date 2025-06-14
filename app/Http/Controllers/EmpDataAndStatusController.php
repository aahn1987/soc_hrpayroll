<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttPayroll;
use App\Models\EmpGeneralInfo;
use App\Http\Controllers\SysAdminLogsController;
use App\Http\Controllers\EmpContractHistoryController;
use App\Http\Controllers\EmpDocumentsController;
use App\Http\Controllers\EmpGeneralInfoController;
use App\Http\Controllers\EmpLeaveBalanceController;
use App\Http\Controllers\EmpObjectivesController;
use App\Http\Controllers\EmpSalaryInformationController;
use App\Http\Controllers\SysLoginDataController;
use Illuminate\Support\Facades\Storage;
class EmpDataAndStatusController extends Controller
{
    public function statuschanger(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        EmpGeneralInfo::query()->update(['employee_status' => 0]);
        $references = AttPayroll::where('pay_month', $month)
            ->where('pay_year', $year)
            ->pluck('soc_reference')
            ->toArray();
        EmpGeneralInfo::whereIn('soc_reference', $references)->update(['employee_status' => 1]);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Payroll',
            'log_details' => "Updated employees statuses updated based on payroll records for {$month} - {$year}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "Employee statuses updated based on payroll records for {$month} - {$year}.",
        ]);
    }
    public function importdata(Request $request)
    {
        //
    }
    public function newemployee(Request $request)
    {
        $employee_reference = 'employee-' . date('YmdHis');
        $objective_token = 'objective-' . date('YmdHis');
        if ($request->gender === 1) {
            $profilepicture = asset(Storage::url('profileimages/male.jpg'));
        } else {
            $profilepicture = asset(Storage::url('profileimages/female.jpg'));
        }
        $username = $request->soc_reference;
        $request->merge([
            'employee_reference' => $employee_reference,
            'profilepicture' => $profilepicture,
            'annual_leave_balance' => 0,
            'sick_leave_balance' => 0,
            'carried_forward_balance' => 0,
            'objective_token' => $objective_token,
            'userpass' => "12345678",
            'username' => $username,
            'userrole' => "employee",
            'user_reference' => $employee_reference,
            'objective_text' => "<p></p>"

        ]);
        $data = $request->all();
        $GenAndJobInfo = new EmpGeneralInfoController;
        $GenAndJobInfo->addgeneralinfo($data);
        $GenAndJobInfo->addjobinfo($data);
        $ContractHistory = new EmpContractHistoryController;
        $ContractHistory->addhistory($data);
        $SalInfo = new EmpSalaryInformationController;
        $SalInfo->addsalary($data);
        $LoginData = new SysLoginDataController;
        $LoginData->addlogindata($data);
        $LeavBal = new EmpLeaveBalanceController;
        $LeavBal->createbalance($data);
        $Objective = new EmpObjectivesController;
        $Objective->creatobjective($data);
        $DocFolder = new EmpDocumentsController;
        $DocFolder->createdirectory($data);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Employees',
            'log_details' => "Added New Employee {$request->soc_reference} Name: {$request->fullname}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "Employee {$request->soc_reference} Name: {$request->fullname} Added Successfully.",
        ]);
    }
}
