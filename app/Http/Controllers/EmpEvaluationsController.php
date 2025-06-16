<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\EmpEvaluations;
use App\Models\EmpListEvaluations;
use App\Models\EmpEvaluated;
use App\Models\EmpTokens;
use App\Models\DataEvaluationSchedule;
use App\Mail\RequestEvaluationMail;
use App\Services\FcmService;
class EmpEvaluationsController extends Controller
{
    public function listevaluations(Request $request)
    {
        $evaluations = EmpListEvaluations::where('soc_reference', $request->soc_reference)->get();
        return response()->json($evaluations);
    }
    public function showevaluation(Request $request)
    {
        $evaluations = EmpListEvaluations::where('eval_token', $request->eval_token)->first();
        return response()->json($evaluations);
    }
    public function requestsupervisor(Request $request)
    {
        $evalinfo = DataEvaluationSchedule::where('is_current', 1)->first();
        $data = $request->all();
        $data['eval_token'] = 'evaluation_' . date('YmdHis');
        $data['request_for_month'] = date("F");
        $data['request_for_year'] = date("Y");
        $data['request_date'] = date("Y-m-d");
        $data['evaluation_requested'] = 1;
        $data['schedule_id'] = $evalinfo->id;
        EmpEvaluations::create($data);
        EmpEvaluated::create($data);
        $approveurl = env('APP_FRONT') . 'evaluation/' . $data['leave_token'] . '/supervisor';
        Mail::to($request->supervisor_email)->send(new RequestEvaluationMail($data, $approveurl));
        return response()->json([
            'success' => true,
            'message' => "Evaluation Request for {$data['request_for_month']} - {$data['request_for_year']} is sent to supervisor successfully."
        ]);
    }
    public function supervisorevaluation(Request $request)
    {
        $evaldata = EmpListEvaluations::where('eval_token', $request->eval_token)->first();
        $data = [];
        $data['contract_duration'] = $request->contract_duration;
        $data['employee_rating'] = $request->employee_rating;
        $data['supervisor_comment'] = $request->supervisor_comment;
        $data['supervisor_eval_date'] = date("Y-m-d");
        $data['supervisor_status'] = 1;
        $data['progress'] = 40;
        EmpEvaluations::where('eval_token', $request->eval_token)->update($data);
        $token = EmpTokens::where('soc_reference', $evaldata->soc_reference)->value('fcm_token');
        if ($token) {
            FcmService::sendNotificationSingle(
                $token,
                'Evaluation Process',
                "{$evaldata->supervisor_name} has finished the evalution for {$evaldata->request_for_month} -{$evaldata->request_for_year}"
            );
        }
        return response()->json([
            'success' => true,
            'message' => "Evaluation Commented Successfully"
        ]);
    }
    public function requestheadofsuboffice(Request $request)
    {
        $evaldata = EmpListEvaluations::where('eval_token', $request->eval_token)->first();
        $data['eval_token'] = $request->eval_token;
        $data['request_for_month'] = $evaldata->request_for_month;
        $data['request_for_year'] = $evaldata->request_for_year;
        $data['fullname'] = $evaldata->fullname;
        $data['objective_text'] = $evaldata->objective_text;
        $data['head_of_sub_office_email'] = $request->head_of_sub_office_email;
        $data['head_of_sub_office_name'] = $request->head_of_sub_office_name;
        EmpEvaluations::where('eval_token', $request->eval_token)->update($data);
        $approveurl = env('APP_FRONT') . 'evaluation/' . $request->eval_token . '/headofsuboffice';
        Mail::to($request->head_of_sub_office_email)->send(new RequestEvaluationMail($data, $approveurl));
        return response()->json([
            'success' => true,
            'message' => "Evaluation Request for {$data['request_for_month']} - {$data['request_for_year']} is sent to Head of Sub office successfully."
        ]);
    }
    public function skipheadofsuboffice(Request $request)
    {
        $evaldata = EmpListEvaluations::where('eval_token', $request->eval_token)->first();
        $evlupdate = EmpEvaluations::where('eval_token', $request->eval_token)->first();
        $evlupdate->head_of_sub_office_name = $evaldata->supervisor_name;
        $evlupdate->head_of_sub_office_email = $evaldata->supervisor_email;
        $evlupdate->head_of_sub_office_comment = $evaldata->supervisor_comment;
        $evlupdate->head_of_sub_office_eval_date = $evaldata->supervisor_eval_date;
        $evlupdate->head_of_sub_office_status = $evaldata->supervisor_status;
        $evlupdate->progress = 70;
        return response()->json([
            'success' => true,
            'message' => "Evaluation Head of Sub office Skipped successfully."
        ]);

    }
    public function headofsubofficeevaluation(Request $request)
    {
        $evaldata = EmpListEvaluations::where('eval_token', $request->eval_token)->first();
        $data = [];
        $data['head_of_sub_office_comment'] = $request->head_of_sub_office_comment;
        $data['head_of_sub_office_eval_date'] = date("Y-m-d");
        $data['head_of_sub_office_status'] = 1;
        $data['progress'] = 70;
        EmpEvaluations::where('eval_token', $request->eval_token)->update($data);
        $token = EmpTokens::where('soc_reference', $evaldata->soc_reference)->value('fcm_token');
        if ($token) {
            FcmService::sendNotificationSingle(
                $token,
                'Evaluation Process',
                "{$evaldata->head_of_sub_office_name} has finished the evalution for {$evaldata->request_for_month} -{$evaldata->request_for_year}"
            );
        }
        return response()->json([
            'success' => true,
            'message' => "Evaluation Commented Successfully"
        ]);
    }
    public function commentevaluatin(Request $request)
    {
        $evaldata = EmpListEvaluations::where('eval_token', $request->eval_token)->first();
        $data = [];
        $data['employee_agreement'] = $request->employee_agreement;
        $data['employee_comment'] = $request->employee_comment;
        $data['employee_comment_date'] = date("Y-m-d");
        $data['employee_status'] = 1;
        $data['progress'] = 100;
        EmpEvaluations::where('eval_token', $request->eval_token)->update($data);
        return response()->json([
            'success' => true,
            'message' => "Evaluation Commented Successfully"
        ]);
    }
    public function deleteevaluation(Request $request)
    {
        $evaldata = EmpListEvaluations::where('eval_token', $request->eval_token)->first();
        $data = [];
        $data['deleted'] = 1;
        EmpEvaluations::where('eval_token', $request->eval_token)->update($data);
        return response()->json([
            'success' => true,
            'message' => "Evaluation Deleted Successfully"
        ]);
    }
}
