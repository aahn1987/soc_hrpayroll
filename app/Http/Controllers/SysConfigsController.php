<?php

namespace App\Http\Controllers;

use App\Models\SysConfigs;
use App\Models\SysAdminLogs;
use Illuminate\Http\Request;

class SysConfigsController extends Controller
{
    public function list()
    {
        $configs = SysConfigs::select('config_name', 'config_key', 'config_value')->get();
        ;
        return response()->json($configs);
    }
    public function update(Request $request)
    {
        $request->validate([
            'configkey' => 'required|string',
            'configvalue' => 'required',
            'adminref' => 'required|string'
        ]);
        $configs = SysConfigs::where('config_key', $request->configkey)->update([
            'config_value' => $request->configvalue
        ]);
        $configinfo = SysConfigs::where('config_key', $request->configkey)->first();
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'System Configuration Edit',
            'log_details' => "Changed {$configinfo->config_name} Status to {$$request->configvalue}"
        ]);
        return response()->json([
            'success' => true,
            'message' => "{$configinfo->config_name} Changed to {$$request->configvalue} Successfully."
        ]);
    }

}
