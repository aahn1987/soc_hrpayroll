<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\SocAdmins;
use App\Models\SocAdminPrivilegs;
use App\Models\SocListAdmins;
use App\Http\Controllers\SysLoginDataController;
use App\Http\Controllers\SysAdminLogsController;
use Illuminate\Http\Request;

class SocAdminsController extends Controller
{
    public function list()
    {
        $socadmins = SocListAdmins::get();
        return response()->json($socadmins);
    }
    public function show(Request $request)
    {
        $socadmins = SocListAdmins::where('refrence', $request->refrence)->first();
        return response()->json($socadmins);
    }
    public function delete(Request $request)
    {
        $socadmin = SocAdmins::where('refrence', $request->refrence)->first();
        $fullname = $socadmin->fullname;
        $emailaddress = $socadmin->emailaddress;
        $phonenumber = $socadmin->phonenumber;
        $socadmin->deleted = 1;
        $socadmin->save();
        $delLogin = new SysLoginDataController();
        $delLogin->deletelogindata($request->refrence);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin',
            'log_details' => "Deleted SOC Admin Name: {$fullname} , Email: {$emailaddress}, Phone: {$phonenumber}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => 'SOC Admin ' . $fullname . ' Deleted Successfully.'
        ]);
    }
    public function update(Request $request)
    {
        $socAdminData = SocAdmins::where('refrence', $request->refrence)->first();
        $socAdminData->fullname = $request->fullname;
        $socAdminData->jobpostition = $request->jobpostition;
        $socAdminData->emailaddress = $request->emailaddress;
        $socAdminData->phonenumber = $request->phonenumber;
        $socAdminData->save();
        $socAdminPriviliges = SocAdminPrivilegs::where('refrence', $request->refrence)->first();
        $socAdminPriviliges->manage_staff = $request->managestaff;
        $socAdminPriviliges->manage_iom = $request->manageiom;
        $socAdminPriviliges->manage_employees = $request->manageemployees;
        $socAdminPriviliges->manage_payroll = $request->managepayroll;
        $socAdminPriviliges->manage_leaves = $request->manageleaves;
        $socAdminPriviliges->manage_evalutions = $request->manageevalutions;
        $socAdminPriviliges->manage_sysconfig = $request->managesysconfig;
        $socAdminPriviliges->save();
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin',
            'log_details' => "Updated SOC Admin Name: {$request->fullname} , Email: {$request->emailaddress}, Phone: {$request->phonenumber}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => 'SOC Admin ' . $request->fullname . ' Updated Successfully.'
        ]);
    }
    public function logininfo(Request $request)
    {
        $logindata = $request->all();
        $editlogindata = new SysLoginDataController;
        $editlogindata->editlogindata($logindata);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin',
            'log_details' => "Updated SOC Admin {$request->fullname} Login Data."
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "SOC Admin {$request->fullname} Login Data Updated Successfully."
        ]);
    }
    public function profileimage(Request $request)
    {
        $socadmin = SocAdmins::where('refrence', $request->refrence)
            ->where('deleted', 0)
            ->first();
        $file = $request->file('profileimage');
        $extension = $file->getClientOriginalExtension();
        $filename = $request->refrence . '.' . $extension;
        $file->storeAs('/profileimages', $filename, 'public');
        $fullUrl = asset('storage/profileimages/' . $filename);
        $socadmin->profileimage = $fullUrl;
        $socadmin->save();
        return response()->json([
            'url' => $fullUrl,
            'success' => true,
            'message' => "Profile Image Updated Successfully."
        ]);
    }
    public function new(Request $request)
    {
        $reference = 'socadmin-' . date('YmdHis');
        SocAdmins::create([
            'refrence' => $reference,
            'fullname' => $request->fullname,
            'jobpostition' => $request->jobpostition,
            'emailaddress' => $request->emailaddress,
            'phonenumber' => $request->phonenumber,
            'profileimage' => asset(Storage::url('profileimages/admin.png')),
        ]);
        SocAdminPrivilegs::create([
            'refrence' => $reference,
            'manage_staff' => boolval($request->managestaff),
            'manage_iom' => boolval($request->manageiom),
            'manage_employees' => boolval($request->manageemployees),
            'manage_payroll' => boolval($request->managepayroll),
            'manage_leaves' => boolval($request->manageleaves),
            'manage_evalutions' => boolval($request->manageevalutions),
            'manage_sysconfig' => boolval($request->managesysconfig),
        ]);
        $logindata = [
            'username' => $request->username,
            'userpass' => $request->userpass,
            'userrole' => 'admin',
            'user_reference' => $reference,
        ];
        $addLogindata = new SysLoginDataController;
        $addLogindata->addlogindata($logindata);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin',
            'log_details' => "Added SOC Admin: Name: {$request->fullname}, Email:  {$request->emailaddress},  {$request->phonenumber}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "SOC Admin  Name:  {$request->fullname}, Email:  {$request->emailaddress}, Phone:  {$request->phonenumber} Added Successfully."
        ]);
    }
    public function profile(Request $request)
    {
        $socAdminData = SocAdmins::where('refrence', $request->reference)->first();
        $socAdminData->fullname = $request->fullname;
        $socAdminData->emailaddress = $request->emailaddress;
        $socAdminData->phonenumber = $request->phonenumber;
        $socAdminData->save();
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin',
            'log_details' => "Updated His/Her Profile Information"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => 'SOC Admin Profile' . $request->fullname . ' Updated Successfully.'
        ]);
    }
    public function account(Request $request)
    {
        $logindata = $request->all();
        $editlogindata = new SysLoginDataController;
        $editlogindata->editlogindata($logindata);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin',
            'log_details' => "Updated His/Her Account Information"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "Your Login Data Updated Successfully."
        ]);
    }
}
