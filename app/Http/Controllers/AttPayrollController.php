<?php

namespace App\Http\Controllers;

use App\Models\AttPayroll;
use App\Models\AttListPayroll;
use App\Models\AttWBS;
use App\Models\AttSumPayroll;
use App\Http\Controllers\SysAdminLogsController;
use Illuminate\Http\Request;

class AttPayrollController extends Controller
{
    public function list(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $listPayroll = AttListPayroll::where('pay_month', $month)->where('pay_year', $year)->get();
        return response()->json($listPayroll);
    }
    public function show(Request $request)
    {
        $listPayroll = AttListPayroll::where('soc_reference', $request->reference)->get();
        return response()->json($listPayroll);
    }
    public function sums(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $sumPayroll = AttSumPayroll::where('pay_month', $month)->where('pay_year', $year)->get();
        return response()->json($sumPayroll);
    }
    public function delete(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        AttPayroll::where('pay_month', $month)->where('pay_year', $year)->update(['deleted' => 0]);
        AttWBS::where('pay_month', $month)->where('pay_year', $year)->update(['deleted' => 0]);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Payroll',
            'log_details' => "Deleted payroll sheet for  {$month} - {$year}."
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'message' => "Payroll  sheet for  {$month} - {$year} deleted successfully.",
            'success' => true,
        ]);
    }
    public function addpayroll($payroll = [])
    {
        $fields = [
            'soc_reference',
            'pay_month',
            'pay_year',
            'import_date',
            'base_salary',
            'communication',
            'attendance',
            'dpta',
            'retors',
            'soc_fees',
            'total_salary',
            'net_salary',
            'general_liabilty',
            'occupational_insurance_amount',
            'health_insurance_by_company',
            'health_insurance_by_employee',
            'social_security_by_company',
            'social_security_by_employee',
            'personal_income_tax',
            'rnr',
            'extrafees',
            'final_net_salary',
            'salary_paid_by_company',
            'estimated_salary',
            'expectation_gap',
            'acions',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $payroll[$field];
        }
        AttPayroll::create($data);
    }
    public function addwbs($wbs = [])
    {
        $fields = [
            'soc_reference',
            'pay_month',
            'pay_year',
            'import_date',
            'wbs1_wbl_for_salaries',
            'percentage1',
            'allocation1',
            'comment_wbs1',
            'wbs2_wbl_for_salaries',
            'percentage2',
            'allocation2',
            'comment_wbs2',
            'wbs3_wbl_for_salaries',
            'percentage3',
            'allocation3',
            'comment_wbs3',
            'wbs4_wbl_for_salaries',
            'percentage4',
            'allocation4',
            'comment_wbs4',
            'wbs5_wbl_for_salaries',
            'percentage5',
            'allocation5',
            'comment_wbs5',
            'wbs6_wbl_for_salaries',
            'percentage6',
            'allocation6',
            'comment_wbs6',
            'wbs7_wbl_for_salaries',
            'percentage7',
            'allocation7',
            'comment_wbs7',
            'wbs8_wbl_for_salaries',
            'percentage8',
            'allocation8',
            'comment_wbs8',
            'total_wbs',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $wbs[$field];
        }
        AttWBS::create($data);
    }
}
