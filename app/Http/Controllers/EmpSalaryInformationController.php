<?php

namespace App\Http\Controllers;

use App\Models\EmpSalaryInformation;
use App\Models\EmpShowSalary;
use Illuminate\Http\Request;

class EmpSalaryInformationController extends Controller
{
    public function getsalary(Request $request)
    {
        $salinfo = EmpShowSalary::where('soc_reference', $request->soc_reference)->get();
        return response()->json($salinfo);
    }
    public function editsalary(Request $request)
    {
        $fields = [
            'soc_reference',
            'basesalary',
            'danger_pay',
            'general_liabilty',
            'occupational_insurance',
            'occupational_insurance_amount',
            'health_isurance',
            'health_insurance_by_company',
            'health_insurance_by_employee',
            'social_security',
            'social_security_by_company',
            'social_security_by_employee',
            'personal_income_tax',
            'extra_fees',
            'rnr',
            'payment_type_id',
            'bankname',
            'account_number_p1',
            'account_number_p2',
            'account_number_p3',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->input($field);
        }
        $employee_reference = $data['soc_reference'];
        EmpSalaryInformation::whereIn('soc_reference', $employee_reference)->update($data);

    }
    public function modifysalary($salinfo = [])
    {
        $fields = [
            'soc_reference',
            'basesalary',
            'danger_pay',
            'general_liabilty',
            'occupational_insurance',
            'occupational_insurance_amount',
            'health_isurance',
            'health_insurance_by_company',
            'health_insurance_by_employee',
            'social_security',
            'social_security_by_company',
            'social_security_by_employee',
            'personal_income_tax',
            'extra_fees',
            'rnr',
            'payment_type_id',
            'bankname',
            'account_number_p1',
            'account_number_p2',
            'account_number_p3',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $salinfo[$field];
        }
        $employee_reference = $data['soc_reference'];
        EmpSalaryInformation::whereIn('soc_reference', $employee_reference)->update($data);

    }
    public function addsalary($salarydata = [])
    {
        $fields = [
            'soc_reference',
            'basesalary',
            'danger_pay',
            'general_liabilty',
            'occupational_insurance',
            'occupational_insurance_amount',
            'health_isurance',
            'health_insurance_by_company',
            'health_insurance_by_employee',
            'social_security',
            'social_security_by_company',
            'social_security_by_employee',
            'personal_income_tax',
            'extra_fees',
            'rnr',
            'payment_type_id',
            'bankname',
            'account_number_p1',
            'account_number_p2',
            'account_number_p3',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $salarydata[$field];
        }
        EmpSalaryInformation::create($data);
    }
}
