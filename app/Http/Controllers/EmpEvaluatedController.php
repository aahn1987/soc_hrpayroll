<?php

namespace App\Http\Controllers;

use App\Models\EmpListEvaluated;
use App\Models\EmpListNotEvaluated;
use App\Models\EmpListEvaluations;
use App\Models\EmpListObjectives;
use Illuminate\Http\Request;

class EmpEvaluatedController extends Controller
{
    public function evaluated(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $evlauted = EmpListEvaluated::where('schedule_month', $month)
            ->where('schedule_year', $year)
            ->get();
        return response()->json($evlauted);
    }
    public function notevaluated(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $notevlauted = EmpListNotEvaluated::where('schedule_month', $month)
            ->where('schedule_year', $year)
            ->get();
        return response()->json($notevlauted);
    }
    public function evaluations(Request $request)
    {
        $from = $request->from_date;
        $to = $request->to_date;
        $evaluations = EmpListEvaluations::whereDate('request_date', '>=', $from)
            ->where('request_date', '<=', $to)
            ->get();
        return response()->json($evaluations);
    }
    public function nobjectives()
    {
        $nobjectives = EmpListObjectives::where('objective_text', '<p></p>')
            ->where('employee_status', 1)
            ->get();
        return response()->json($nobjectives);
    }
    public function pendingbjectives()
    {
        $pendingbjectives = EmpListObjectives::where('objective_text', 'NOT LIKE', '%<p></p>%')
            ->where('employee_status', 1)->where('supervisor_approval', operator: 0)->orWhere('head_of_sub_office_approval', 0)
            ->get();
        return response()->json($pendingbjectives);
    }
    public function approvedbjectives()
    {
        $approvedbjectives = EmpListObjectives::where('objective_text', 'NOT LIKE', '%<p></p>%')
            ->where('employee_status', 1)->where('supervisor_approval', operator: 1)->where('head_of_sub_office_approval', 1)
            ->get();
        return response()->json($approvedbjectives);
    }
}
