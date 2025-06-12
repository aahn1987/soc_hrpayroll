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
        AttPayroll::create([
            'soc_reference' => $payroll['soc_reference'],
            'pay_month' => $payroll['pay_month'],
            'pay_year' => $payroll['pay_year'],
            'import_date' => date('Y-m-d'),
            'base_salary' => $payroll['base_salary'],
            'communication' => $payroll['communication'],
            'attendance' => $payroll['attendance'],
            'danger_pay' => $payroll['danger_pay'],
            'retors' => $payroll['retors'],
            'soc_fees' => $payroll['soc_fees'],
            'total_salary' => $payroll['total_salary'],
            'net_salary' => $payroll['net_salary'],
            'general_liabilty' => $payroll['general_liabilty'],
            'occupational_insurance_amount' => $payroll['occupational_insurance_amount'],
            'health_insurance_by_company' => $payroll['health_insurance_by_company'],
            'health_insurance_by_employee' => $payroll['health_insurance_by_employee'],
            'social_security_by_company' => $payroll['social_security_by_company'],
            'social_security_by_employee' => $payroll['social_security_by_employee'],
            'personal_income_tax' => $payroll['personal_income_tax'],
            'rnr' => $payroll['rnr'],
            'extrafees' => $payroll['extrafees'],
            'final_net_salary' => $payroll['final_net_salary'],
            'salary_paid_by_company' => $payroll['salary_paid_by_company'],
            'estimated_salary' => $payroll['estimated_salary'],
            'expectation_gap' => $payroll['expectation_gap'],
            'acions' => $payroll['acions'],
        ]);
    }
    public function addwbs($wbs = [])
    {
        AttWBS::create([
            'soc_reference' => $wbs['soc_reference'],
            'pay_month' => $wbs['pay_month'],
            'pay_year' => $wbs['pay_year'],
            'import_date' => date('Y-m-d'),
            'wbs1_wbl_for_salaries' => $wbs['wbs1_wbl_for_salaries'],
            'percentage1' => $wbs['percentage1'],
            'allocation1' => $wbs['allocation1'],
            'comment_wbs1' => $wbs['comment_wbs1'],
            'wbs2_wbl_for_salaries' => $wbs['wbs2_wbl_for_salaries'],
            'percentage2' => $wbs['percentage2'],
            'allocation2' => $wbs['allocation2'],
            'comment_wbs2' => $wbs['comment_wbs2'],
            'wbs3_wbl_for_salaries' => $wbs['wbs3_wbl_for_salaries'],
            'percentage3' => $wbs['percentage3'],
            'allocation3' => $wbs['allocation3'],
            'comment_wbs3' => $wbs['comment_wbs3'],
            'wbs4_wbl_for_salaries' => $wbs['wbs4_wbl_for_salaries'],
            'percentage4' => $wbs['percentage4'],
            'allocation4' => $wbs['allocation4'],
            'comment_wbs4' => $wbs['comment_wbs4'],
            'wbs5_wbl_for_salaries' => $wbs['wbs5_wbl_for_salaries'],
            'percentage5' => $wbs['percentage5'],
            'allocation5' => $wbs['allocation5'],
            'comment_wbs5' => $wbs['comment_wbs5'],
            'wbs6_wbl_for_salaries' => $wbs['wbs6_wbl_for_salaries'],
            'percentage6' => $wbs['percentage6'],
            'allocation6' => $wbs['allocation6'],
            'comment_wbs6' => $wbs['comment_wbs6'],
            'wbs7_wbl_for_salaries' => $wbs['wbs7_wbl_for_salaries'],
            'percentage7' => $wbs['percentage7'],
            'allocation7' => $wbs['allocation7'],
            'comment_wbs7' => $wbs['comment_wbs7'],
            'wbs8_wbl_for_salaries' => $wbs['wbs8_wbl_for_salaries'],
            'percentage8' => $wbs['percentage8'],
            'allocation8' => $wbs['allocation8'],
            'comment_wbs8' => $wbs['comment_wbs8'],
            'total_wbs' => $wbs['total_wbs'],
        ]);
    }
}
