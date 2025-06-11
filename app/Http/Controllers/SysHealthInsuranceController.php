<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\SysHealthInsurance;
use App\Models\SysHealthInsuranceFiles;
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
        $request->validate([
            'id' => 'required'
        ]);
        $healthinsurancefiles = SysHealthInsuranceFiles::select('filename', 'langauge', 'filelink', 'id')->where('id', $request->id)->get();
        return response()->json($healthinsurancefiles);
    }
    public function editfile(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'filename' => 'required|string',
            'langauge' => 'required|string',
        ]);
        $healthinsurancefile = SysHealthInsuranceFiles::where('id', $request->id)->first();
        if ($request->hasFile('filelink')) {
            $request->validate([
                'filelink' => 'file|mimes:pdf,doc,docx|max:16384',
            ]);
            $nfn = 'healthins-' . date('YmdHis');
            $file = $request->file('filelink');
            $extension = $file->getClientOriginalExtension();
            $filename = $nfn . '.' . $extension;
            $path = $file->storeAs('/healthinsurancefiles', $filename);
            $fullUrl = asset(Storage::url($path));

            $healthinsurancefile->filelink = $fullUrl;
        }
        $healthinsurancefile->filename = $request->filename;
        $healthinsurancefile->langauge = $request->langauge;
        $healthinsurancefile->save();
        return response()->json([
            'success' => true,
            'message' => "Health insurance file updated successfully.",
        ]);
    }
    public function editinfo(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'text' => 'required|string',
            'langauge' => 'required|string',
        ]);
        $healthinsurance = SysHealthInsurance::where('id', $request->id)->first();
        if ($request->hasFile('video_url')) {
            $request->validate([
                'video_url' => 'file|mimes:pdf,doc,docx|max:16384',
            ]);
            $nfn = 'healthins-' . date('YmdHis');
            $file = $request->file('video_url');
            $extension = $file->getClientOriginalExtension();
            $filename = $nfn . '.' . $extension;
            $path = $file->storeAs('/healthinsurancevideos', $filename);
            $fullUrl = asset(Storage::url($path));
            $healthinsurance->video_url = $fullUrl;
        }
        $healthinsurance->text = $request->text;
        $healthinsurance->langauge = $request->langauge;
        $healthinsurance->save();
        return response()->json([
            'success' => true,
            'message' => "Health insurance info updated successfully.",
        ]);
    }
    public function addfile(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'filelink' => 'required|file|mimes:pdf,doc,docx|max:16384',
            'filename' => 'required|string',
            'langauge' => 'required|string',
        ]);
        $nfn = 'healthins-' . date('YmdHis');
        $file = $request->file('filelink');
        $extension = $file->getClientOriginalExtension();
        $filename = $nfn . '.' . $extension;
        $path = $file->storeAs('/healthinsurancefiles', $filename);
        $fullUrl = asset(Storage::url($path));
        SysHealthInsuranceFiles::create([
            'filename' => $request->filename,
            'langauge' => $request->langauge,
            'filelink' => $request->filelink,
        ]);
        return response()->json([
            'url' => $fullUrl,
            'success' => true,
            'message' => "Health Insurance File Added Successfully."
        ]);
    }
    public function deletefile(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $healthinsurancefiles = SysHealthInsuranceFiles::where('id', $request->id)->get();
        $healthinsurancefiles->deleted = 1;
        $healthinsurancefiles->save();
        return response()->json([
            'success' => true,
            'message' => "Health Insurance file {$healthinsurancefiles->filename} Deleted Successfully."
        ]);
    }
}
