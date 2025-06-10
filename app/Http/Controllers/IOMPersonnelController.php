<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\IOMPersonnel;
use App\Models\IomListPersonnel;
use App\Models\SysLoginData;
use App\Models\SysAdminLogs;
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
        $request->validate([
            'refrence' => 'required|string'
        ]);
        $personnel = IomPersonnel::where('refrence', $request->refrence)
            ->where('deleted', 0)
            ->first();
        return response()->json($personnel);
    }
    public function delete(Request $request)
    {
        $request->validate([
            'refrence' => 'required|string',
            'adminref' => 'required|string'
        ]);
        $personnel = IomPersonnel::where('refrence', $request->refrence)->first();
        $oldName = $personnel->fullname;
        $oldEmail = $personnel->emailaddress;
        $oldPhone = $personnel->phonenumber;
        $personnel->deleted = 1;
        $personnel->save();
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'IOM Personnel Update',
            'log_details' => "Deleted IOM personnel Name: {$oldName} , Email: {$oldEmail}, Phone: {$oldPhone}"
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Personnel Deleted Successfully.'
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'refrence' => 'required|string',
            'fullname' => 'required|string',
            'emailaddress' => 'required|email',
            'phonenumber' => 'nullable|string',
            'adminref' => 'required|string'
        ]);
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
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'IOM Personnel Update',
            'log_details' => "Updated IOM personnel: Name: {$oldName} → {$request->fullname}, Email: {$oldEmail} → {$request->emailaddress}, Phone: {$oldPhone} → {$request->phonenumber}"
        ]);
        return response()->json([
            'success' => true,
            'message' => "Personnel  Name: {$oldName} → {$request->fullname}, Email: {$oldEmail} → {$request->emailaddress}, Phone: {$oldPhone} → {$request->phonenumber} Updated Successfully."
        ]);
    }
    public function loginifo(Request $request)
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
            'log_action' => 'IOM Personnel Update',
            'log_details' => "Updated IOM HR {$request->fullname} Login Data."
        ]);
        return response()->json([
            'success' => true,
            'message' => "IOM HR  {$request->fullname} Login Data Updated Successfully."
        ]);
    }
    public function profileimage(Request $request)
    {
        $request->validate([
            'refrence' => 'required|string',
            'profileimage' => 'required|file|image|max:2048',
        ]);
        $personnel = IomPersonnel::where('refrence', $request->refrence)
            ->where('deleted', 0)
            ->first();
        $file = $request->file('profileimage');
        $extension = $file->getClientOriginalExtension();
        $filename = $request->refrence . '.' . $extension;
        $path = $file->storeAs('/profileimages', $filename);
        $fullUrl = asset(Storage::url($path));
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
        $request->validate([
            'fullname' => 'required|string|max:255',
            'emailaddress' => 'required|email|max:255',
            'phonenumber' => 'nullable|string|max:255',
            'username' => 'required|string',
            'userpass' => 'required|string',
            'adminref' => 'required|string'
        ]);

        $reference = 'iomhr-' . date('YmdHis');
        IomPersonnel::create([
            'reference' => $reference,
            'fullname' => $request->fullname,
            'emailaddress' => $request->emailaddress,
            'phonenumber' => $request->phonenumber,
            'profileimage' => asset(Storage::url('/profileimages/default.jpg')),
        ]);
        SysLoginData::create([
            'username' => $request->username,
            'userpass' => $request->userpass,
            'userrole' => 'organization',
            'user_reference' => $reference,
        ]);
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'IOM Personnel Update',
            'log_details' => "Updated IOM personnel: Name: {$request->fullname}, Email:  {$request->emailaddress},  {$request->phonenumber}"
        ]);
        return response()->json([
            'success' => true,
            'message' => "Personnel  Name:  {$request->fullname}, Email:  {$request->emailaddress}, Phone:  {$request->phonenumber} Added Successfully."
        ]);
    }
    public function editprofile(Request $request)
    {
        $request->validate([
            'refrence' => 'required|string',
            'fullname' => 'required|string',
            'emailaddress' => 'required|email',
            'phonenumber' => 'nullable|string',
        ]);
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
    public function access(Request $request)
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
        return response()->json([
            'success' => true,
            'message' => "Your Login Data Updated Successfully."
        ]);
    }
}
