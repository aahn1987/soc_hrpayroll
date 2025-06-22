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
        $loglist = SocListLogs::where('refrence', $request->refrence)->get();
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
    public function addlog($logsdata = [])
    {
        SysAdminLogs::create([
            'refrence' => $logsdata['refrence'],
            'log_action' => $logsdata['log_action'],
            'log_details' => $logsdata['log_details']
        ]);
    }
}
