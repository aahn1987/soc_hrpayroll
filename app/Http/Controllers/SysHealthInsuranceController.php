<?php

namespace App\Http\Controllers;

use App\Models\SysHealthInsurance;
use App\Models\SysHealthInsuranceFiles;
use Illuminate\Http\Request;

class SysHealthInsuranceController extends Controller
{
    public function listfiles()
    {
        $healthinsurancefiles = SysHealthInsuranceFiles::select('filename', 'langauge', 'filelink', 'id')->get();
        return response()->json($healthinsurancefiles);
    }
    public function listinfo()
    {
        $healthinsuranceinfo = SysHealthInsurance::select('text', 'langauge', 'video_url', 'id')->get();
        return response()->json($healthinsuranceinfo);
    }
    public function showfile(Request $request)
    {
        //
    }
    public function editfile(Request $request)
    {
        //
    }
    public function editinfo(Request $request)
    {
        //
    }
    public function addfile(Request $request)
    {
        //
    }
    public function deletefile(Request $request)
    {
        //
    }
}
