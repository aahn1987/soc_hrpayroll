<?php

namespace App\Http\Controllers;

use App\Models\SysAdminLogs;
use App\Models\SocListLogs;
use Illuminate\Http\Request;

class SysAdminLogsController extends Controller
{
    public function alllogs()
    {
        $loglist = SocListLogs::get();
        return response()->json($loglist);
    }
    public function logsbyadmin(Request $request)
    {
        $request->validate([
            'ref' => 'required|string',
        ]);
        $loglist = SocListLogs::where('refrence', $request->ref)->get();
        return response()->json($loglist);
    }
    public function clearlogs()
    {
        SysAdminLogs::truncate();
        return response()->json([
            'message' => "Logs Cleared Successfully.",
            'success' => true,
        ]);
    }
}
