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
use App\Http\Controllers\DataSystemDataController;
use App\Http\Controllers\AttPayrollController;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
        $file = $request->file('paysheet');
        $filename = 'data_import_' . $request->month . "_" . $request->year . "_" . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('/excelsheets', $filename, 'public');
        $fullpath = asset(Storage::url($path));
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        unset($rows[0]);
        $countAll = 0;
        $countNewEmployees = 0;
        $countUpdatedContracts = 0;
        $controllers = [
            'data' => new DataSystemDataController,
            'general' => new EmpGeneralInfoController,
            'contract' => new EmpContractHistoryController,
            'salary' => new EmpSalaryInformationController,
            'login' => new SysLoginDataController,
            'leave' => new EmpLeaveBalanceController,
            'objective' => new EmpObjectivesController,
            'documents' => new EmpDocumentsController,
            'payroll' => new AttPayrollController,
        ];
        $data = [];
        foreach ($rows as $index => $row) {
            $countAll++;
            $empref = 'employee-' . date('YmdHis') . $index;
            $objective_token = 'objective-' . date('YmdHis') . $index;
            $data = [
                'employee_reference' => $empref,
                'soc_reference' => $row[0],
                'pay_month' => $request->month,
                'pay_year' => $request->year,
                'import_date' => date('Y-m-d'),
                'fullname' => $row[1],
                'hub_id' => $controllers['data']->hubid($row[2]),
                'duty_station_id' => $controllers['data']->dutystationid($row[3]),
                'danger_pay' => $controllers['data']->dangerpayid($row[4]),
                'contract_id' => $controllers['data']->contracttypeid($row[5]),
                'job_position' => $row[6],
                'division_id' => $controllers['data']->divisonid($row[7]),
                'staff_grouping_id' => $controllers['data']->staffgroupingid($row[8]),
                'programme_id' => $controllers['data']->programmeid($row[9]),
                'grade_id' => $controllers['data']->gradeid($row[10]),
                'employee_step' => (integer) $row[11],
                'basesalary' => excel_float($row[12]),
                'base_salary' => excel_float($row[12]),
                'communication' => excel_float($row[13]),
                'attendance' => excel_float($row[14]),
                'dpta' => excel_float($row[15]),
                'retors' => excel_float($row[16]),
                'soc_fees' => excel_float($row[17]),
                'total_salary' => excel_float($row[18]),
                'net_salary' => excel_float($row[19]),
                'general_liabilty' => excel_float($row[20]),
                'occupational_insurance' => check_excel_flag($row[21]),
                'occupational_insurance_amount' => excel_float($row[21]),
                'health_isurance' => check_excel_flag($row[22]),
                'health_insurance_by_company' => excel_float($row[22]),
                'health_insurance_by_employee' => excel_float($row[23]),
                'social_security' => check_excel_flag($row[24]),
                'social_security_by_company' => excel_float($row[24]),
                'social_security_by_employee' => excel_float($row[25]),
                'personal_income_tax' => excel_float($row[26]),
                'rnr' => excel_float($row[27]),
                'extra_fees' => excel_float($row[28]),
                'extrafees' => excel_float($row[28]),
                'final_net_salary' => excel_float($row[29]),
                'salary_paid_by_company' => excel_float($row[30]),
                'estimated_salary' => excel_float($row[31]),
                'expectation_gap' => excel_float($row[32]),
                'acions' => $row[33],
                'working_hours' => $row[34],
                'svn_hired' => $row[35],
                'wbs1_wbl_for_salaries' => $row[36],
                'percentage1' => excel_float($row[37]),
                'allocation1' => excel_float($row[38]),
                'comment_wbs1' => $row[39],
                'wbs2_wbl_for_salaries' => $row[40],
                'percentage2' => excel_float($row[41]),
                'allocation2' => excel_float($row[42]),
                'comment_wbs2' => $row[43],
                'wbs3_wbl_for_salaries' => $row[44],
                'percentage3' => excel_float($row[45]),
                'allocation3' => excel_float($row[46]),
                'comment_wbs3' => $row[47],
                'wbs4_wbl_for_salaries' => $row[48],
                'percentage4' => excel_float($row[49]),
                'allocation4' => excel_float($row[50]),
                'comment_wbs4' => $row[51],
                'wbs5_wbl_for_salaries' => $row[52],
                'percentage5' => excel_float($row[53]),
                'allocation5' => excel_float($row[54]),
                'comment_wbs5' => $row[55],
                'wbs6_wbl_for_salaries' => $row[56],
                'percentage6' => excel_float($row[57]),
                'allocation6' => excel_float($row[58]),
                'comment_wbs6' => $row[59],
                'wbs7_wbl_for_salaries' => $row[60],
                'percentage7' => excel_float($row[61]),
                'allocation7' => excel_float($row[62]),
                'comment_wbs7' => $row[63],
                'wbs8_wbl_for_salaries' => $row[64],
                'percentage8' => excel_float($row[65]),
                'allocation8' => excel_float($row[66]),
                'comment_wbs8' => $row[67],
                'total_wbs' => excel_float($row[68]),
                'first_employement_date' => date("Y-m-d", timestamp: strtotime(datetime: $row[69])),
                'soc_employemrnt_date' => date("Y-m-d", timestamp: strtotime(datetime: $row[70])),
                'contract_end' => date("Y-m-d", timestamp: strtotime(datetime: $row[71])),
                'contract_start' => date("Y-m-d", timestamp: strtotime(datetime: $row[72])),
                'eligible_for_upgrade' => check_excel_flag($row[73]),
                'employee_link' => $row[74],
                'gender' => $controllers['data']->genderid($row[75]),
                'no_of_dependants' => excel_float($row[76]),
                'blood_group' => $row[77],
                'date_of_birth' => excel_date($row[78]),
                'age' => calculate_age(excel_date($row[78]), $row[79]),
                'marital_status' => $controllers['data']->maritalstatusid($row[80]),
                'nationality' => $row[81],
                'contract_category_id' => $controllers['data']->contractcategoriesid($row[82]),
                'emailaddress' => "",
                'phonenumber' => "",
                'homeaddress' => $row[85],
                'account_number_p1' => $row[86],
                'account_number_p2' => $row[87],
                'account_number_p3' => $row[88],
                'bankname' => $row[89],
                'payment_type' => excel_payment($row[89]),
                'payment_type_id' => excel_payment($row[89]),
                'contract_remarks' => $row[90],
                'supervisor_name' => "",
                'supervisor_email' => "",
                'head_of_sub_office_name' => "",
                'head_of_sub_office_email' => "",
                'username' => $row[0],
                'userpass' => md5("123456"),
                'userrole' => "employee",
                'user_reference' => $empref,
                'objective_text' => "<p></p>",
                'objective_token' => $objective_token,
                'annual_leave_balance' => 0,
                'sick_leave_balance' => 0,
                'carried_forward_balance' => 0,
                'profilepicture' => asset(Storage::url(
                    'profileimages/' . clear_string($row[75]) . ".png"
                )),
            ];
            $exists = $controllers['general']->empexist($data['soc_reference'])->getData()->exists ?? 0;

            if ($exists === 1) {
                $currentEnd = $controllers['contract']->contractend($data['soc_reference']);
                if ($data['contract_end'] !== $currentEnd) {
                    $controllers['contract']->clearcurrent($data['soc_reference']);
                    $controllers['contract']->addhistory($data);
                    $controllers['salary']->modifysalary($data);
                    $countUpdatedContracts++;
                }
            } else {
                $controllers['general']->addgeneralinfo($data);
                $controllers['general']->addjobinfo($data);
                $controllers['contract']->addhistory($data);
                $controllers['salary']->addsalary($data);
                $controllers['login']->addlogindata($data);
                $controllers['leave']->createbalance($data);
                $controllers['objective']->creatobjective($data);
                $controllers['documents']->createdirectory($data);
                $countNewEmployees++;
            }

            $controllers['payroll']->addpayroll($data);
            $controllers['payroll']->addwbs($data);
        }
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Payroll',
            'log_details' => "Imported Excel Sheet {$fullpath} to Employees"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "Data Imported Successfully from Excel Sheet.",
            'details' => [
                'total_rows' => $countAll,
                'new_employees' => $countNewEmployees,
                'updated_contracts' => $countUpdatedContracts,
                'excel_file_path' => $fullpath
            ]
        ]);
    }
    public function newemployee(Request $request)
    {
        $employee_reference = 'employee-' . date('YmdHis');
        $objective_token = 'objective-' . date('YmdHis');
        if ($request->gender == 1) {
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
            'userpass' => "123456",
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
