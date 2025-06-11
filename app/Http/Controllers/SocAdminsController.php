<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\SocAdmins;
use App\Models\SocAdminPrivilegs;
use App\Models\SocListAdmins;
use App\Models\SysAdminLogs;
use App\Models\SysLoginData;
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
        $request->validate([
            'refrence' => 'required|string'
        ]);
        $socadmins = SocListAdmins::where('refrence', $request->refrence)->first();
        return response()->json($socadmins);
    }
    public function delete(Request $request)
    {
        $request->validate([
            'refrence' => 'required|string',
            'adminref' => 'required|string'
        ]);
        $socadmin = SocListAdmins::where('refrence', $request->refrence)->first();
        $fullname = $socadmin->fullname;
        $emailaddress = $socadmin->emailaddress;
        $phonenumber = $socadmin->phonenumber;
        $socadmin->deleted = 1;
        $socadmin->save();
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin Delete',
            'log_details' => "Deleted SOC Admin Name: {$fullname} , Email: {$emailaddress}, Phone: {$phonenumber}"
        ]);
        return response()->json([
            'success' => true,
            'message' => 'SOC Admin ' . $fullname . ' Deleted Successfully.'
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'refrence' => 'required|string',
            'fullname' => 'required|string',
            'jobpostition' => 'required|string',
            'emailaddress' => 'required|email',
            'phonenumber' => 'nullable|string',
            'managestaff' => 'required',
            'manageiom' => 'required',
            'manageemployees' => 'required',
            'managepayroll' => 'required',
            'manageleaves' => 'required',
            'manageevalutions' => 'required',
            'managesysconfig' => 'required',
            'adminref' => 'required|string'
        ]);
        $socAdminData = SocAdmins::where('reference', $request->reference)->first();
        $socAdminData->fullname = $request->fullname;
        $socAdminData->jobpostition = $request->jobpostition;
        $socAdminData->emailaddress = $request->emailaddress;
        $socAdminData->phonenumber = $request->phonenumber;
        $socAdminData->save();
        $socAdminPriviliges = SocAdminPrivilegs::where('reference', $request->reference)->first();
        $socAdminPriviliges->manage_staff = boolval($request->managestaff);
        $socAdminPriviliges->manage_iom = boolval($request->manageiom);
        $socAdminPriviliges->manage_employees = boolval($request->manageemployees);
        $socAdminPriviliges->manage_payroll = boolval($request->managepayroll);
        $socAdminPriviliges->manage_leaves = boolval($request->manageleaves);
        $socAdminPriviliges->manage_evalutions = boolval($request->manageevalutions);
        $socAdminPriviliges->manage_sysconfig = boolval($request->managesysconfig);
        $socAdminPriviliges->save();
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin Update',
            'log_details' => "Updated SOC Admin Name: {$request->fullname} , Email: {$request->emailaddress}, Phone: {$request->phonenumber}"
        ]);
        return response()->json([
            'success' => true,
            'message' => 'SOC Admin ' . $request->fullname . ' Updated Successfully.'
        ]);
    }
    public function logininfo(Request $request)
    {
        $request->validate([
            'refrence' => 'required|string',
            'username' => 'required|string|max:255',
            'fullname' => 'required|string',
            'password' => 'nullable|string',
            'adminref' => 'required|string'
        ]);
        $loginData = SysLoginData::where('user_reference', $request->reference)->first();
        $loginData->username = $request->username;
        if ($request->filled('password')) {
            $loginData->userpass = md5($request->password);
        }

        $loginData->save();
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin Update',
            'log_details' => "Updated SOC Admin {$request->fullname} Login Data."
        ]);
        return response()->json([
            'success' => true,
            'message' => "SOC Admin {$request->fullname} Login Data Updated Successfully."
        ]);
    }
    public function profileimage(Request $request)
    {
        $request->validate([
            'refrence' => 'required|string',
            'profileimage' => 'required|file|image|max:16384',
        ]);
        $socadmin = SocAdmins::where('refrence', $request->refrence)
            ->where('deleted', 0)
            ->first();
        $file = $request->file('profileimage');
        $extension = $file->getClientOriginalExtension();
        $filename = $request->refrence . '.' . $extension;
        $path = $file->storeAs('/profileimages', $filename);
        $fullUrl = asset(Storage::url($path));
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
        $request->validate([
            'refrence' => 'required|string',
            'fullname' => 'required|string',
            'jobpostition' => 'required|string',
            'emailaddress' => 'required|email',
            'phonenumber' => 'nullable|string',
            'managestaff' => 'required',
            'manageiom' => 'required',
            'manageemployees' => 'required',
            'managepayroll' => 'required',
            'manageleaves' => 'required',
            'manageevalutions' => 'required',
            'managesysconfig' => 'required',
            'username' => 'required|string',
            'userpass' => 'required|string',
            'adminref' => 'required|string'
        ]);
        $reference = 'socadmin-' . date('YmdHis');
        SocAdmins::create([
            'reference' => $reference,
            'fullname' => $request->fullname,
            'jobpostition' => $request->jobpostition,
            'emailaddress' => $request->emailaddress,
            'phonenumber' => $request->phonenumber,
            'profileimage' => asset(Storage::url('/profileimages/default.jpg')),
        ]);
        SocAdminPrivilegs::create([
            'reference' => $reference,
            'manage_staff' => boolval($request->managestaff),
            'manage_iom' => boolval($request->manageiom),
            'manage_employees' => boolval($request->manageemployees),
            'manage_payroll' => boolval($request->managepayroll),
            'manage_leaves' => boolval($request->manageleaves),
            'manage_evalutions' => boolval($request->manageevalutions),
            'manage_sysconfig' => boolval($request->managesysconfig),
        ]);
        SysLoginData::create([
            'username' => $request->username,
            'userpass' => $request->userpass,
            'userrole' => 'admin',
            'user_reference' => $reference,
        ]);
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin Added',
            'log_details' => "Added SOC Admin: Name: {$request->fullname}, Email:  {$request->emailaddress},  {$request->phonenumber}"
        ]);
        return response()->json([
            'success' => true,
            'message' => "SOC Admin  Name:  {$request->fullname}, Email:  {$request->emailaddress}, Phone:  {$request->phonenumber} Added Successfully."
        ]);
    }
    public function profile(Request $request)
    {
        $request->validate([
            'refrence' => 'required|string',
            'fullname' => 'required|string',
            'emailaddress' => 'required|email',
            'phonenumber' => 'nullable|string',
            'adminref' => 'required|string'
        ]);
        $socAdminData = SocAdmins::where('reference', $request->reference)->first();
        $socAdminData->fullname = $request->fullname;
        $socAdminData->emailaddress = $request->emailaddress;
        $socAdminData->phonenumber = $request->phonenumber;
        $socAdminData->save();
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'SOC Admin Profile Update',
            'log_details' => "Updated His/Her Profile Information"
        ]);
        return response()->json([
            'success' => true,
            'message' => 'SOC Admin Profile' . $request->fullname . ' Updated Successfully.'
        ]);
    }
    public function account(Request $request)
    { {
            $request->validate([
                'refrence' => 'required|string',
                'username' => 'required|string|max:255',
                'fullname' => 'required|string',
                'password' => 'nullable|string',
                'adminref' => 'required|string'
            ]);
            $loginData = SysLoginData::where('user_reference', $request->reference)->first();
            $loginData->username = $request->username;
            if ($request->filled('password')) {
                $loginData->userpass = md5($request->password);
            }
            $loginData->save();
            SysAdminLogs::create([
                'refrence' => $request->adminref,
                'log_action' => 'SOC Admin Account Data',
                'log_details' => "Updated His/Her Account Information"
            ]);
            return response()->json([
                'success' => true,
                'message' => "Your Login Data Updated Successfully."
            ]);
        }
    }
}
