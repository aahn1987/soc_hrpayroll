<?php

namespace App\Http\Controllers;

use App\Models\SysConfigs;
use Illuminate\Http\Request;
use App\Http\Controllers\SysAdminLogsController;
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
        $configs = SysConfigs::where('config_key', $request->configkey)->first();
        $configs->config_value = $request->configvalue;
        $configs->save();
        if ($request->configvalue == 1) {
            $action = 'On';
        } else {
            $action = 'Off';
        }
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'System Configuration',
            'log_details' => "Changed {$configs->config_name} Status to {$action}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "{$configs->config_name} Changed to {$action} Successfully."
        ]);
    }

}
