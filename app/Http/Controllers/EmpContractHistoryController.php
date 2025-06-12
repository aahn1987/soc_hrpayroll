<?php

namespace App\Http\Controllers;

use App\Models\EmpContractHistory;
use App\Models\EmpShowHistory;
use Illuminate\Http\Request;

class EmpContractHistoryController extends Controller
{
    public function showhistory(Request $request)
    {
        $history = EmpShowHistory::where('soc_reference', $request->reference)->get();
        return response()->json($history);
    }
    public function getcurrent(Request $request, $histroydata = [])
    {
        $fields = [
            'soc_reference',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->filled($field) ? $request->input($field) : ($histroydata[$field] ?? null);
        }
        $history = EmpShowHistory::where('soc_reference', $data['soc_reference'])->where('is_current', 1)->first();
        return response()->json($history);
    }
    public function addhistory(Request $request, $histroydata = [])
    {
        $fields = [
            'soc_reference',
            'hub_id',
            'duty_station_id',
            'contract_id',
            'job_position',
            'division_id',
            'staff_grouping_id',
            'programme_id',
            'grade_id',
            'employee_step',
            'working_hours',
            'first_employement_date',
            'soc_employemrnt_date',
            'contract_start',
            'contract_end',
            'eligible_for_upgrade',
            'contract_category_id',
            'contract_remarks',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->filled($field) ? $request->input($field) : ($histroydata[$field] ?? null);
        }
        EmpContractHistory::create($data);
    }
    public function edithistory(Request $request, $histroydata = [])
    {
        $fields = [
            'soc_reference',
            'hub_id',
            'duty_station_id',
            'contract_id',
            'job_position',
            'division_id',
            'staff_grouping_id',
            'programme_id',
            'grade_id',
            'employee_step',
            'working_hours',
            'first_employement_date',
            'soc_employemrnt_date',
            'contract_start',
            'contract_end',
            'eligible_for_upgrade',
            'contract_category_id',
            'contract_remarks',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->filled($field) ? $request->input($field) : ($histroydata[$field] ?? null);
        }
        $soc_reference = $data['soc_reference'];
        EmpContractHistory::where('soc_reference', $soc_reference)->where('is_current', 1)->update($data);
    }
    public function clearcurrent(Request $request, $histroydata = [])
    {
        $fields = [
            'soc_reference',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->filled($field) ? $request->input($field) : ($histroydata[$field] ?? null);
        }
        $socReference = $data['soc_reference'];
        EmpContractHistory::where('soc_reference', $socReference)->update(['is_current' => 0]);

    }
    public function deletehistory(Request $request, $histroydata = [])
    {
        $fields = [
            'id',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->filled($field) ? $request->input($field) : ($histroydata[$field] ?? null);
        }
        $id = $data['id'];
        EmpContractHistory::where('id', $id)->update(['deleted' => 1]);

    }
    public function contractend(Request $request, $histroydata = [])
    {
        $fields = [
            'soc_reference',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->filled($field) ? $request->input($field) : ($histroydata[$field] ?? null);
        }
        $history = EmpContractHistory::select('contract_end')->where('soc_reference', $data['soc_reference'])->where('is_current', 1)->first();
        return $history->contract_end;

    }
}
