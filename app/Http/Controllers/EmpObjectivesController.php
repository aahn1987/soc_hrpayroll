<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\EmpTokens;
use App\Models\EmpListObjectives;
use App\Models\EmpObjectives;
use App\Services\FcmService;
use App\Mail\ObjectiveUpdateRequestMail;
use App\Http\Controllers\EmpNotificationsController;
class EmpObjectivesController extends Controller
{
    public function getobjectivebyemp(Request $request)
    {
        $obj = EmpListObjectives::where('soc_reference', $request->soc_reference)->first();
        return response()->json($obj);
    }
    public function getobjectivebytoken(Request $request)
    {
        $obj = EmpListObjectives::where('objective_token', $request->objective_token)->first();
        return response()->json($obj);
    }
    public function creatobjective($obj = [])
    {
        $fields = [
            'soc_reference',
            'objective_text',
            'objective_token',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $obj[$field];
        }
        EmpObjectives::create($data);
    }
    public function editobjective(Request $request)
    {
        $data = [];
        $data['supervisor_name'] = $request->supervisor_name;
        $data['supervisor_email'] = $request->supervisor_email;
        $data['with_head_of_sub_office'] = $request->with_head_of_sub_office;
        $data['head_of_sub_office_name'] = $request->head_of_sub_office_name;
        $data['head_of_sub_office_email'] = $request->head_of_sub_office_email;
        $data['objective_text'] = $request->objective_text;
        $objlink = env('APP_FRONT') . 'objective/' . $request->objective_token . '/approve/supervisor';
        EmpObjectives::whereIn('objective_token', $request->objective_token)->update($data);
        Mail::to($request->supervisor_email)->send(
            new ObjectiveUpdateRequestMail(
                $request->objective_text,
                $objlink,
                $request->fullname,
                $request->soc_reference
            )
        );
        return response()->json([
            'success' => true,
            'message' => "Your objective updated successfully and sent to supervisor : {$request->supervisor_name} email: {$request->supervisor_email}"
        ]);
    }
    public function approvebysupervisor(Request $request)
    {
        $objective = EmpObjectives::where('objective_token', $request->objective_token)->firstOrFail();
        $objective->supervisor_approval = 1;
        if ($objective->with_head_of_sub_office == 1) {
            $objlink = env('APP_FRONT') . 'objective/' . $request->objective_token . '/approve/headofsuboffice';
            Mail::to($request->head_of_sub_office_email)->send(
                new ObjectiveUpdateRequestMail(
                    $objective->objective_text,
                    $objlink,
                    $request->fullname,
                    $objective->soc_reference
                )
            );
            $token = EmpTokens::where('soc_reference', $objective->soc_reference)->value('fcm_token');
            if ($token) {
                FcmService::sendNotificationSingle(
                    $token,
                    'Objective Process',
                    $objective->supervisor_name . ' has approved your objective, waiting for head of sub office approval.'
                );
            }
        } else {
            $objective->head_of_sub_office_approval = 1;
        }
        $objective->save();
        return response()->json([
            'success' => true,
            'message' => 'Objective approved successfully.'
        ]);
    }
    public function approvebyheadofsuboffice(Request $request)
    {
        $objective = EmpObjectives::where('objective_token', $request->c)->firstOrFail();
        $objective->head_of_sub_office_approval = 1;
        $objective->save();
        $token = EmpTokens::where('soc_reference', $objective->soc_reference)->value('fcm_token');
        if ($token) {
            FcmService::sendNotificationSingle(
                $token,
                'Objective Process',
                $objective->head_of_sub_office_name . ' has approved your objective, waiting for head of sub office approval.'
            );
        }
        return response()->json([
            'success' => true,
            'message' => 'Objective approved successfully.'
        ]);

    }
}
