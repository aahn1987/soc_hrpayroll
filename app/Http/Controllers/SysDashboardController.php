<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysEmployeesStats;
use App\Models\SysLastTenEvaluations;
use App\Models\SysLastTenLeaves;
use App\Models\SysLeavesStats;
class SysDashboardController extends Controller
{
    public function empstats()
    {
        $empstat = SysEmployeesStats::get();
        return response()->json($empstat);
    }
    public function lastteneval()
    {
        $lastteneval = SysLastTenEvaluations::get();
        return response()->json($lastteneval);
    }
    public function lasttenleave()
    {
        $lasttenleave = SysLastTenLeaves::get();
        return response()->json($lasttenleave);
    }
    public function leavestats()
    {
        $leavestats = SysLeavesStats::get();
        return response()->json($leavestats);
    }
}
