<?php

namespace App\Http\Controllers;

use App\Models\EmpGeneralInfo;
use App\Models\EmpShowInfo;
use App\Models\EmpJobInformation;
use Illuminate\Http\Request;

class EmpGeneralInfoController extends Controller
{
    public function showall()
    {
        $emplist = EmpShowInfo::select('employee_reference', 'profilepicture', 'soc_reference', 'fullname')->get();
        return response()->json($emplist);
    }
    public function showinfo(Request $request)
    {
        $emplist = EmpShowInfo::where('employee_reference', $request->reference)->get();
        return response()->json($emplist);
    }
    public function getSoc(Request $request)
    {
        $refget = EmpGeneralInfo::select('soc_reference')->where('employee_reference', $request->employee_reference)->first();
        return response()->json($refget);
    }
    public function editgeneralinfo(Request $request)
    {
        $fields = [
            'employee_reference',
            'fullname',
            'gender',
            'marital_status',
            'no_of_dependants',
            'blood_group',
            'date_of_birth',
            'age',
            'nationality',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->input($field);
        }
        $employee_reference = $data['employee_reference'];
        EmpGeneralInfo::where('employee_reference', $employee_reference)->update($data);
    }
    public function editcontactinfo(Request $request)
    {
        $fields = [
            'employee_reference',
            'emailaddress',
            'phonenumber',
            'homeaddress',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->input($field);
        }
        $employee_reference = $data['employee_reference'];
        EmpGeneralInfo::where('employee_reference', $employee_reference)->update($data);
    }
    public function editstatus(Request $request)
    {
        $fields = [
            'employee_reference',
            'employee_status',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->input($field);
        }
        $employee_reference = $data['employee_reference'];
        $sts = $data['employee_status'];
        EmpGeneralInfo::whereIn('soc_reference', $employee_reference)->update(['employee_status' => $sts]);
    }
    public function addgeneralinfo($informationdata = [])
    {
        $fields = [
            'employee_reference',
            'soc_reference',
            'fullname',
            'profilepicture',
            'gender',
            'marital_status',
            'no_of_dependants',
            'blood_group',
            'date_of_birth',
            'age',
            'nationality',
            'emailaddress',
            'phonenumber',
            'homeaddress',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $informationdata[$field];
        }
        EmpGeneralInfo::create($data);
    }
    public function editjobinfo(Request $request)
    {
        $fields = [
            'soc_reference',
            'svn_hired',
            'employee_link',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->input($field);
        }
        $employee_reference = $data['employee_reference'];
        EmpJobInformation::whereIn('soc_reference', $employee_reference)->update($data);
    }
    public function editsupervisors(Request $request)
    {
        $fields = [
            'soc_reference',
            'supervisor_name',
            'supervisor_email',
            'head_of_sub_office_name',
            'head_of_sub_office_email',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->input($field);
        }
        $employee_reference = $data['soc_reference'];
        EmpJobInformation::whereIn('soc_reference', $employee_reference)->update($data);
    }
    public function addjobinfo($informationdata = [])
    {
        $fields = [
            'soc_reference',
            'svn_hired',
            'employee_link',
            'supervisor_name',
            'supervisor_email',
            'head_of_sub_office_name',
            'head_of_sub_office_email',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $informationdata[$field];
        }
        EmpJobInformation::create($data);
    }
    public function empexist($ref)
    {
        $exists = EmpGeneralInfo::where('soc_reference', $ref)->exists();

        return response()->json([
            'exists' => $exists ? 1 : 0
        ]);
    }
}
