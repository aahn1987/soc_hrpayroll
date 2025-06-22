<?php

namespace App\Http\Controllers;

use App\Models\EmpLeaveRequests;
use App\Models\EmpListLeaves;
use App\Models\EmpLeaveBalance;
use Illuminate\Http\Request;
use App\Http\Controllers\DataSystemDataController;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequestMail;
use App\Mail\LeaveCancelMail;
use App\Models\EmpTokens;
use App\Services\FcmService;
class EmpLeaveRequestsController extends Controller
{
    public function listleave(Request $request)
    {
        $leaves = EmpListLeaves::where('soc_reference', $request->soc_reference)->get();
        return response()->json($leaves);
    }
    public function showleave(Request $request)
    {
        $leaves = EmpListLeaves::where('leave_token', $request->leave_token)->first();
        return response()->json($leaves);
    }
    public function requestleave(Request $request)
    {
        $data = [];
        $data['fullname'] = $request->fullname;
        $data['leave_token'] = 'leave-' . date('YmdHis');
        $data['soc_reference'] = $request->soc_reference;
        $data['leave_type_id'] = $request->leave_type_id;
        $leavename = new DataSystemDataController()->requestleavetype($request->leave_type_id);
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['leave_reason'] = $request->leave_reason;
        $data['days'] = $request->days;
        $data['supervisor_name'] = $request->supervisor_name;
        $data['supervisor_email'] = $request->supervisor_email;
        $data['with_head_of_sub_office'] = $request->with_head_of_sub_office;
        if ($request->with_head_of_sub_office === true) {
            $data['head_of_sub_office_name'] = $request->head_of_sub_office_name;
            $data['head_of_sub_office_email'] = $request->head_of_sub_office_email;
        } else {
            $data['head_of_sub_office_name'] = $request->supervisor_name;
            $data['head_of_sub_office_email'] = $request->supervisor_email;
        }
        $data['with_report'] = $request->with_report;
        if ($request->with_report === true) {
            $approveurl = env('APP_FRONT') . 'leave/' . $data['leave_token'] . '/approve/soc';
            $emailreciever = env('SOC_LEAVE_EMAIL');
            $file = $request->file('report_file');
            $filename = 'leave_report_' . $data['leave_token'] . "_" . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/leavefiles', $filename, 'public');
            $fullUrl = asset('storage/leavefiles/' . $filename);
            $data['report_url'] = $fullUrl;
        } else {
            $approveurl = env('APP_FRONT') . 'leave/' . $data['leave_token'] . '/approve/supervisor';
            $data['report_url'] = NULL;
        }
        EmpLeaveRequests::create($data);
        Mail::to($emailreciever)->send(new LeaveRequestMail($data, $leavename, $approveurl));
        return response()->json([
            'success' => true,
            'message' => "{$leavename} Request for {$data['leave_reason']} From {$data['start_date']} to {$data['end_date']} for {$data['days']} days is sent successfully."
        ]);
    }
    public function approvesoc(Request $request)
    {
        $leavedata = EmpListLeaves::where('leave_token', $request->leave_token)->first();
        $data = [];
        $data['fullname'] = $leavedata->fullname;
        $data['leave_token'] = $leavedata->leave_token;
        $data['soc_reference'] = $leavedata->soc_reference;
        $data['leave_type_id'] = $leavedata->leave_type_id;
        $leavename = $leavedata->leave_type;
        $data['start_date'] = $leavedata->start_date;
        $data['end_date'] = $leavedata->end_date;
        $data['leave_reason'] = $leavedata->leave_reason;
        $data['days'] = $leavedata->days;
        $data['supervisor_name'] = $leavedata->supervisor_name;
        $data['supervisor_email'] = $leavedata->supervisor_email;
        $approveurl = env('APP_FRONT') . 'leave/' . $request->leave_token . '/approve/supervisor';
        Mail::to($leavedata->supervisor_email)->send(new LeaveRequestMail($data, $leavename, $approveurl));
        return response()->json([
            'success' => true,
            'message' => "{$leavename} request for {$data['leave_reason']} From {$data['start_date']} to {$data['end_date']} for {$data['days']} days is approved and sent to supervisor successfully."
        ]);
    }
    public function approvesupervisor(Request $request)
    {
        $leavedata = EmpListLeaves::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate = EmpLeaveRequests::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate->supervisor_acceptance = $request->acceptance;
        $leavtoupdate->supervisor_remarks = $request->remarks;
        $notificationbody = '';
        $message = '';
        if ($leavedata->with_head_of_sub_office == 1) {
            $leavtoupdate->save();
            $approveurl = env('APP_FRONT') . 'leave/' . $leavedata->leave_token . '/approve/headofsuboffice';
            $data = [
                'fullname' => $leavedata->fullname,
                'leave_token' => $leavedata->leave_token,
                'soc_reference' => $leavedata->soc_reference,
                'leave_type_id' => $leavedata->leave_type_id,
                'start_date' => $leavedata->start_date,
                'end_date' => $leavedata->end_date,
                'leave_reason' => $leavedata->leave_reason,
                'days' => $leavedata->days,
                'report_url' => $leavedata->report_url ?? null,
            ];
            Mail::to($leavedata->head_of_sub_office_email)
                ->send(new LeaveRequestMail($data, $leavedata->leave_type, $approveurl));
            if ($request->acceptance == 1) {
                $message = "{$leavedata->leave_type} request from {$leavedata->start_date} to {$leavedata->end_date} approved by supervisor and sent to head of sub office.";
                $notificationbody = "Your {$leavedata->leave_type} request has been approved by {$leavedata->supervisor_name} and forwarded.";
            } else {
                $message = "{$leavedata->leave_type} request from {$leavedata->start_date} to {$leavedata->end_date} rejected by supervisor and sent to head of sub office.";
                $notificationbody = "Your {$leavedata->leave_type} request has been rejected by {$leavedata->supervisor_name} and forwarded.";
            }
        } else {
            $leavtoupdate->head_of_sub_office_acceptance = $request->acceptance;
            $leavtoupdate->head_of_sub_office_remarks = $request->remarks;
            $leavtoupdate->save();
            if ($request->acceptance == 1) {
                $baldata = EmpLeaveBalance::where('soc_reference', $leavedata->soc_reference)->first();
                $leave_days = $leavedata->days;
                if (in_array($leavedata->leave_type_id, [1, 2])) {
                    if ($leave_days <= $baldata->annual_leave_balance) {
                        $baldata->annual_leave_balance -= $leave_days;
                    } else {
                        $diff = $leave_days - $baldata->annual_leave_balance;
                        $baldata->annual_leave_balance = 0;
                        $baldata->carried_forward_balance -= $diff;
                    }
                } elseif (in_array($leavedata->leave_type_id, [3, 4, 5])) {
                    if ($leave_days <= $baldata->sick_leave_balance) {
                        $baldata->sick_leave_balance -= $leave_days;
                    } else {
                        $baldata->sick_leave_balance = 0;
                    }
                }
                $baldata->save();
                $message = "{$leavedata->leave_type} request from {$leavedata->start_date} to {$leavedata->end_date} approved successfully.";
                $notificationbody = "Your {$leavedata->leave_type} request has been approved by {$leavedata->supervisor_name}.";
            } else {
                $message = "{$leavedata->leave_type} request from {$leavedata->start_date} to {$leavedata->end_date} rejected successfully.";
                $notificationbody = "Your {$leavedata->leave_type} request has been rejected by {$leavedata->supervisor_name}.";
            }
        }
        $token = EmpTokens::where('soc_reference', $leavedata->soc_reference)->value('fcm_token');
        if ($token) {
            FcmService::sendNotificationSingle(
                $token,
                'Leave Process',
                $notificationbody
            );
        }
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    public function approveheadofsuboffice(Request $request)
    {
        $leavedata = EmpListLeaves::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate = EmpLeaveRequests::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate->head_of_sub_office_acceptance = $request->acceptance;
        $leavtoupdate->head_of_sub_office_remarks = $request->remarks;
        $notificationbody = '';
        $message = '';
        if ($request->acceptance == 1) {
            $baldata = EmpLeaveBalance::where('soc_reference', $leavedata->soc_reference)->first();
            $leave_days = $leavedata->days;
            if (in_array($leavedata->leave_type_id, [1, 2])) {
                if ($leave_days <= $baldata->annual_leave_balance) {
                    $baldata->annual_leave_balance -= $leave_days;
                } else {
                    $diff = $leave_days - $baldata->annual_leave_balance;
                    $baldata->annual_leave_balance = 0;
                    $baldata->carried_forward_balance -= $diff;
                }
            } elseif (in_array($leavedata->leave_type_id, [3, 4, 5])) {
                if ($leave_days <= $baldata->sick_leave_balance) {
                    $baldata->sick_leave_balance -= $leave_days;
                } else {
                    $baldata->sick_leave_balance = 0;
                }
            }
            $baldata->save();
            $message = "{$leavedata->leave_type} request from {$leavedata->start_date} to {$leavedata->end_date} approved successfully.";
            $notificationbody = "Your {$leavedata->leave_type} request has been approved by {$leavedata->superhead_of_sub_office_namevisor_name}.";
        } else {
            $message = "{$leavedata->leave_type} request from {$leavedata->start_date} to {$leavedata->end_date} rejected successfully.";
            $notificationbody = "Your {$leavedata->leave_type} request has been rejected by {$leavedata->head_of_sub_office_name}.";
        }
        $leavtoupdate->save();
        $token = EmpTokens::where('soc_reference', $leavedata->soc_reference)->value('fcm_token');
        if ($token) {
            FcmService::sendNotificationSingle(
                $token,
                'Leave Process',
                $notificationbody
            );
        }
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    public function requestcancellation(Request $request)
    {
        $leavedata = EmpListLeaves::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate = EmpLeaveRequests::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate->leave_canceled = 1;
        $leavtoupdate->cancellation_date = date('Y-m-d');
        $leavtoupdate->save();
        $data = [];
        $data['fullname'] = $leavedata->fullname;
        $data['leave_token'] = $leavedata->leave_token;
        $data['soc_reference'] = $leavedata->soc_reference;
        $data['leave_type_id'] = $leavedata->leave_type_id;
        $leavename = $leavedata->leave_type;
        $data['start_date'] = $leavedata->start_date;
        $data['end_date'] = $leavedata->end_date;
        $data['leave_reason'] = $leavedata->leave_reason;
        $data['days'] = $leavedata->days;
        $data['supervisor_name'] = $leavedata->supervisor_name;
        $data['supervisor_email'] = $leavedata->supervisor_email;
        $approveurl = env('APP_FRONT') . 'leave/' . $request->leave_token . '/cancel/supervisor';
        Mail::to($leavedata->supervisor_email)->send(new LeaveCancelMail($data, $leavename, $approveurl));
        return response()->json([
            'success' => true,
            'message' => "{$leavename} cancel request for {$data['leave_reason']} From {$data['start_date']} to {$data['end_date']} for {$data['days']} days is sent to supervisor successfully."
        ]);
    }
    public function cancelsupervisor(Request $request)
    {
        $leavedata = EmpListLeaves::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate = EmpLeaveRequests::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate->supervisor_cancellation = $request->acceptance;
        $leavtoupdate->supervisor_remarks = $request->remarks;
        $notificationbody = '';
        $message = '';
        if ($leavedata->with_head_of_sub_office == 1) {
            $leavtoupdate->save();
            $approveurl = env('APP_FRONT') . 'leave/' . $leavedata->leave_token . '/cancel/headofsuboffice';
            $data = [
                'fullname' => $leavedata->fullname,
                'leave_token' => $leavedata->leave_token,
                'soc_reference' => $leavedata->soc_reference,
                'leave_type_id' => $leavedata->leave_type_id,
                'start_date' => $leavedata->start_date,
                'end_date' => $leavedata->end_date,
                'leave_reason' => $leavedata->leave_reason,
                'days' => $leavedata->days,
                'report_url' => $leavedata->report_url ?? null,
            ];
            Mail::to($leavedata->head_of_sub_office_email)
                ->send(new LeaveCancelMail($data, $leavedata->leave_type, $approveurl));
            if ($request->acceptance == 1) {
                $message = "{$leavedata->leave_type} cancellation request from {$leavedata->start_date} to {$leavedata->end_date} approved by supervisor and sent to head of sub office.";
                $notificationbody = "Your {$leavedata->leave_type} cancellation request has been approved by {$leavedata->supervisor_name} and forwarded.";
            } else {
                $message = "{$leavedata->leave_type} cancellation request from {$leavedata->start_date} to {$leavedata->end_date} rejected by supervisor and sent to head of sub office.";
                $notificationbody = "Your {$leavedata->leave_type} cancellation request has been rejected by {$leavedata->supervisor_name} and forwarded.";
            }
        } else {
            $leavtoupdate->head_of_sub_office_cancellation = $request->acceptance;
            $leavtoupdate->head_of_sub_office_remarks = $request->remarks;
            $leavtoupdate->save();
            if ($request->acceptance == 1) {
                $baldata = EmpLeaveBalance::where('soc_reference', $leavedata->soc_reference)->first();
                $leave_days = $leavedata->days;
                if (in_array($leavedata->leave_type_id, [1, 2])) {
                    $baldata->annual_leave_balance += $leave_days;
                } elseif (in_array($leavedata->leave_type_id, [3, 4, 5])) {
                    $baldata->sick_leave_balance += $leave_days;
                }
                $baldata->save();
                $message = "{$leavedata->leave_type} cancellation request from {$leavedata->start_date} to {$leavedata->end_date} approved successfully.";
                $notificationbody = "Your {$leavedata->leave_type} cancellation request has been approved by {$leavedata->supervisor_name}.";
            } else {
                $message = "{$leavedata->leave_type} cancellation request from {$leavedata->start_date} to {$leavedata->end_date} rejected successfully.";
                $notificationbody = "Your {$leavedata->leave_type} cancellation request has been rejected by {$leavedata->supervisor_name}.";
            }
        }
        $token = EmpTokens::where('soc_reference', $leavedata->soc_reference)->value('fcm_token');
        if ($token) {
            FcmService::sendNotificationSingle(
                $token,
                'Leave Process',
                $notificationbody
            );
        }
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    public function cancelheadofsuboffice(Request $request)
    {
        $leavedata = EmpListLeaves::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate = EmpLeaveRequests::where('leave_token', $request->leave_token)->firstOrFail();
        $leavtoupdate->head_of_sub_office_cancellation = $request->acceptance;
        $leavtoupdate->head_of_sub_office_remarks = $request->remarks;
        $notificationbody = '';
        $message = '';
        if ($request->acceptance == 1) {
            $baldata = EmpLeaveBalance::where('soc_reference', $leavedata->soc_reference)->first();
            $leave_days = $leavedata->days;
            if (in_array($leavedata->leave_type_id, [1, 2])) {
                $baldata->annual_leave_balance += $leave_days;
            } elseif (in_array($leavedata->leave_type_id, [3, 4, 5])) {
                $baldata->sick_leave_balance += $leave_days;
            }
            $baldata->save();
            $message = "{$leavedata->leave_type} cancellation request from {$leavedata->start_date} to {$leavedata->end_date} approved successfully.";
            $notificationbody = "Your {$leavedata->leave_type} cancellation request has been approved by {$leavedata->superhead_of_sub_office_namevisor_name}.";
        } else {
            $message = "{$leavedata->leave_type} cancellation request from {$leavedata->start_date} to {$leavedata->end_date} rejected successfully.";
            $notificationbody = "Your {$leavedata->leave_type} cancellation request has been rejected by {$leavedata->head_of_sub_office_name}.";
        }
        $leavtoupdate->save();
        $token = EmpTokens::where('soc_reference', $leavedata->soc_reference)->value('fcm_token');
        if ($token) {
            FcmService::sendNotificationSingle(
                $token,
                'Leave Process',
                $notificationbody
            );
        }
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
