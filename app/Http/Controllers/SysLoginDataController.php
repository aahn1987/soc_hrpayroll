<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Models\SysLoginData;
use App\Models\SocAdmins;
use App\Models\IOMPersonnel;
use App\Models\EmpGeneralInfo;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;

class SysLoginDataController extends Controller
{
    public function login(Request $request)
    {
        $user = SysLoginData::where('username', $request->username)
            ->where('userpass', $request->userpass)
            ->where('deleted', 0)
            ->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials please try again.',
            ]);
        }

        return response()->json([
            'success' => true,
            'userrole' => $user->userrole,
            'user_reference' => $user->user_reference,
            'message' => "Successfully logged in, redirecting in 5 seconds"
        ]);
    }
    public function reset(Request $request)
    {
        $user = SysLoginData::where('username', $request->username)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials please try again.',
            ]);
        }
        $newPassword = random_password(10);
        $role = strtolower($user->userrole);
        $reference = $user->user_reference;
        $email = null;
        if ($role === 'admin') {
            $admin = SocAdmins::where('refrence', $reference)->first();
            $email = $admin?->emailaddress;
        } elseif ($role === 'organization') {
            $org = IomPersonnel::where('refrence', $reference)->first();
            $email = $org?->emailaddress;
        } elseif ($role === 'employee') {
            $emp = EmpGeneralInfo::where('employee_reference', $reference)->first();
            $email = $emp?->emailaddress;
        }
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found for this user.',
                'role' => $user->userrole
            ]);
        }
        $user->userpass = md5($newPassword);
        $user->save();
        $fullname = $admin?->fullname ?? $org?->fullname ?? $emp?->fullname ?? $user->username;
        Mail::to($email)->send(new ResetPasswordMail($fullname, $newPassword));
        return response()->json([
            'success' => true,
            'message' => "New password has been created and sent to your email {$email}.",
        ]);
    }
    public function addlogindata(array $logindata = [])
    {
        SysLoginData::create([
            'username' => $logindata['username'],
            'userpass' => md5($logindata['userpass']),
            'userrole' => $logindata['userrole'],
            'user_reference' => $logindata['user_reference'],
        ]);
    }
    public function editlogindata(array $logindata = [])
    {
        $updatelogindata = SysLoginData::where('user_reference', $logindata['user_reference'])->first();
        $updatelogindata->username = $logindata['username'];
        if (filled($logindata['userpass'])) {
            $updatelogindata->userpass = md5($logindata['userpass']);
        }
        $updatelogindata->save();
    }
    public function deletelogindata($user_reference)
    {
        $updatelogindata = SysLoginData::where('user_reference', $user_reference)->first();
        $updatelogindata->deleted = 1;
        $updatelogindata->save();
    }
}
