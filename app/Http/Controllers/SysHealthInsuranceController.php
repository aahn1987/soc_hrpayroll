<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\SysHealthInsurance;
use App\Models\SysHealthInsuranceFiles;
use App\Http\Controllers\SysAdminLogsController;
use Illuminate\Http\Request;

class SysHealthInsuranceController extends Controller
{
    public function listfiles()
    {
        $healthinsurancefiles = SysHealthInsuranceFiles::select('filename', 'langauge', 'filelink', 'id')->where('deleted', 0)->get();
        return response()->json($healthinsurancefiles);
    }
    public function listinfo()
    {
        $healthinsuranceinfo = SysHealthInsurance::select('text', 'langauge', 'video_url', 'id')->get();
        return response()->json($healthinsuranceinfo);
    }
    public function showfile(Request $request)
    {
        $healthinsurancefiles = SysHealthInsuranceFiles::select('filename', 'langauge', 'filelink', 'id')->where('id', $request->id)->get();
        return response()->json($healthinsurancefiles);
    }
    public function editfile(Request $request)
    {
        $healthinsurancefile = SysHealthInsuranceFiles::where('id', $request->id)->first();
        if ($request->hasFile('filelink')) {
            $nfn = 'healthins-' . date('YmdHis');
            $file = $request->file('filelink');
            $extension = $file->getClientOriginalExtension();
            $filename = $nfn . '.' . $extension;
            $file->storeAs('/healthinsurancevideos', $filename, 'public');
            $fullUrl = asset('storage/healthinsurancevideos/' . $filename);

            $healthinsurancefile->filelink = $fullUrl;
        }
        $healthinsurancefile->filename = $request->filename;
        $healthinsurancefile->langauge = $request->langauge;
        $healthinsurancefile->save();
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Health Insurance',
            'log_details' => "Updated health insurance file Name:{$healthinsurancefile->filename}→{$request->filename}, Language:{$healthinsurancefile->langauge}→{$request->langauge}, Link:{$healthinsurancefile->filelink}→{$fullUrl}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "Health insurance file updated successfully.",
        ]);
    }
    public function editinfo(Request $request)
    {
        $healthinsurance = SysHealthInsurance::where('id', $request->id)->first();
        if ($request->hasFile('video_url')) {
            $nfn = 'healthins-' . date('YmdHis');
            $file = $request->file('video_url');
            $extension = $file->getClientOriginalExtension();
            $filename = $nfn . '.' . $extension;
            $file->storeAs('/healthinsurancevideos', $filename, 'public');
            $fullUrl = asset('storage/healthinsurancevideos/' . $filename);
            $healthinsurance->video_url = $fullUrl;
        }
        $healthinsurance->text = $request->text;
        $healthinsurance->langauge = $request->langauge;
        $healthinsurance->save();
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Health Insurance',
            'log_details' => "Updated health insurance info Language:{$healthinsurance->langauge}→{$request->langauge}, Link:{$healthinsurance->video_url}→{$fullUrl}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "Health insurance info updated successfully.",
        ]);
    }
    public function addfile(Request $request)
    {
        $nfn = 'healthins-' . date('YmdHis');
        $file = $request->file('filelink');
        $extension = $file->getClientOriginalExtension();
        $filename = $nfn . '.' . $extension;
        $file->storeAs('/healthinsurancefiles', $filename, 'public');
        $fullUrl = asset('storage/healthinsurancefiles/' . $filename);
        SysHealthInsuranceFiles::create([
            'filename' => $request->filename,
            'langauge' => $request->langauge,
            'filelink' => $request->filelink,
        ]);
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Health Insurance',
            'log_details' => "Added health insurance file Name:{$request->filename}, Language:{$request->langauge}, Link:{$fullUrl}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'url' => $fullUrl,
            'success' => true,
            'message' => "Health Insurance File Added Successfully."
        ]);
    }
    public function deletefile(Request $request)
    {
        $healthinsurancefiles = SysHealthInsuranceFiles::where('id', $request->id)->get();
        $healthinsurancefiles->deleted = 1;
        $healthinsurancefiles->save();
        $logdata = [
            'refrence' => $request->adminref,
            'log_action' => 'Health Insurance',
            'log_details' => "Deleted health insurance file Name:{$healthinsurancefiles->filename}, Language:{$healthinsurancefiles->langauge}, Link:{$healthinsurancefiles->filelink}"
        ];
        $logadd = new SysAdminLogsController;
        $logadd->addlog($logdata);
        return response()->json([
            'success' => true,
            'message' => "Health Insurance file {$healthinsurancefiles->filename} Deleted Successfully."
        ]);
    }
}
