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
        $configs = SysConfigs::where('config_key', $request->configkey)->first();
        $configs->config_value = $request->configvalue;
        $configs->save();
        if ($request->configvalue == 1) {
            $action = 'On';
        } else {
            $action = 'Off';
        }
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'System Configuration Edit',
            'log_details' => "Changed {$configs->config_name} Status to {$action}"
        ]);
        return response()->json([
            'success' => true,
            'message' => "{$configs->config_name} Changed to {$action} Successfully."
        ]);
    }

}
