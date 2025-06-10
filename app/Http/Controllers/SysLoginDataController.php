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
        $request->validate([
            'username' => 'required|string',
            'userpass' => 'required|string',
        ]);
        $user = SysLoginData::where('username', $request->username)
            ->where('userpass', $request->userpass)
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
        $request->validate([
            'username' => 'required|string',
        ]);
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
            $emp = EmpGeneralInfo::where('soc_reference', $reference)->first();
            $email = $emp?->emailaddress;
        }
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found for this user.',
            ]);
        }
        $user->userpass = md5($newPassword);
        $user->save();
        $fullname = $admin?->fullname ?? $org?->fullname ?? $emp?->fullname ?? $user->username;
        Mail::to($email)->send(new ResetPasswordMail($fullname, $newPassword));
        return response()->json([
            'success' => true,
            'message' => 'New password sent to your email.',
        ]);
    }
}
