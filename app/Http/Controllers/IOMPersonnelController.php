<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\IOMPersonnel;
use App\Models\IomListPersonnel;
use App\Http\Controllers\SysLoginDataController;
use App\Http\Controllers\SysAdminLogsController;
use Illuminate\Http\Request;
class IOMPersonnelController extends Controller
{
    public function list()
    {
        $personnel = IomListPersonnel::where('deleted', 0)->get();
        return response()->json($personnel);
    }
    public function show(Request $request)
    {
        $personnel = IomListPersonnel::where('refrence', $request->refrence)
            ->where('deleted', 0)
            ->first();
        return response()->json($personnel);
    }
    public function delete(Request $request)
    {
        $personnel = IomPersonnel::where('refrence', $request->refrence)->first();
        $oldName = $personnel->fullname;
        $oldEmail = $personnel->emailaddress;
        $oldPhone = $personnel->phonenumber;
        $personnel->deleted = 1;
        $delLogin = new SysLoginDataController();
        $delLogin->deletelogindata($request->refrence);
        $personnel->save();
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'IOM HR',
            'log_details' => "Deleted IOM HR Name: {$oldName} , Email: {$oldEmail}, Phone: {$oldPhone}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => 'HR ' . $oldName . ' Deleted Successfully.'
        ]);
    }
    public function update(Request $request)
    {
        $personnel = IomPersonnel::where('refrence', $request->refrence)
            ->where('deleted', 0)
            ->first();
        $oldName = $personnel->fullname;
        $oldEmail = $personnel->emailaddress;
        $oldPhone = $personnel->phonenumber;
        $personnel->fullname = $request->fullname;
        $personnel->emailaddress = $request->emailaddress;
        $personnel->phonenumber = $request->phonenumber;
        $personnel->save();
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'IOM HR',
            'log_details' => "Updated IOM HR: Name: {$oldName} → {$request->fullname}, Email: {$oldEmail} → {$request->emailaddress}, Phone: {$oldPhone} → {$request->phonenumber}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "HR  Name: {$oldName} → {$request->fullname}, Email: {$oldEmail} → {$request->emailaddress}, Phone: {$oldPhone} → {$request->phonenumber} Updated Successfully."
        ]);
    }
    public function loginifo(Request $request)
    {
        $logindata = $request->all();
        $editlogindata = new SysLoginDataController;
        $editlogindata->editlogindata($logindata);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'IOM HR',
            'log_details' => "Updated IOM HR {$request->fullname} Login Data."
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "IOM HR  {$request->fullname} Login Data Updated Successfully."
        ]);
    }
    public function profileimage(Request $request)
    {
        $personnel = IomPersonnel::where('refrence', $request->refrence)
            ->where('deleted', 0)
            ->first();
        $file = $request->file('profileimage');
        $extension = $file->getClientOriginalExtension();
        $filename = $request->refrence . '.' . $extension;
        $file->storeAs('/profileimages', $filename, 'public');
        $fullUrl = asset('storage/profileimages/' . $filename);
        $personnel->profileimage = $fullUrl;
        $personnel->save();
        return response()->json([
            'url' => $fullUrl,
            'success' => true,
            'message' => "Profile Image Updated Successfully."
        ]);
    }
    public function new(Request $request)
    {
        $refrence = 'iomhr-' . date('YmdHis');
        IomPersonnel::create([
            'refrence' => $refrence,
            'fullname' => $request->fullname,
            'emailaddress' => $request->emailaddress,
            'phonenumber' => $request->phonenumber,
            'profileimage' => asset(Storage::url('/profileimages/hr.png')),
        ]);
        $logindata = [
            'username' => $request->username,
            'userpass' => $request->userpass,
            'userrole' => 'organization',
            'user_reference' => $refrence,
        ];
        $addLogindata = new SysLoginDataController;
        $addLogindata->addlogindata($logindata);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'IOM HR',
            'log_details' => "Added IOM HR: Name: {$request->fullname}, Email:  {$request->emailaddress},  {$request->phonenumber}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "IOM HR  Name:  {$request->fullname}, Email:  {$request->emailaddress}, Phone:  {$request->phonenumber} Added Successfully."
        ]);
    }
    public function profile(Request $request)
    {
        $personnel = IomPersonnel::where('refrence', $request->refrence)
            ->where('deleted', 0)
            ->first();
        $personnel->fullname = $request->fullname;
        $personnel->emailaddress = $request->emailaddress;
        $personnel->phonenumber = $request->phonenumber;
        $personnel->save();
        return response()->json([
            'success' => true,
            'message' => "Profile Updated Successfully."
        ]);
    }
    public function account(Request $request)
    {

        $logindata = $request->all();
        $editlogindata = new SysLoginDataController;
        $editlogindata->editlogindata($logindata);
        return response()->json([
            'success' => true,
            'message' => "Your Login Data Updated Successfully."
        ]);

    }
}
