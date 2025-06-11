<?php

namespace App\Http\Controllers;

use App\Models\SysTraining;
use App\Models\SysAdminLogs;
use Illuminate\Http\Request;

class SysTrainingController extends Controller
{
    public function list()
    {
        $trainnglist = SysTraining::select('id', 'training_name', 'training_link')->where('deleted', 0)->get();
        return response()->json($trainnglist);
    }

    public function show(Request $request)
    {
        $request->validate(['id' => 'required',]);
        $trainnglist = SysTraining::select('id', 'training_name', 'training_link')->where('id', $request->id)->first();
        return response()->json($trainnglist);
    }
    public function new(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'trainingname' => 'required|string',
            'traininglink' => 'required|string',
            'adminref' => 'required|string'
        ]);
        SysTraining::create(['training_name' => $request->trainingname, 'training_link' => $request->traininglink,]);
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'Mandatory Training',
            'log_details' => "Added {$request->trainingname} to Mandatory Training"
        ]);
        return response()->json([
            'success' => true,
            'message' => "Mandatory Training {$request->trainingname} Added Successfully."
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'trainingname' => 'required|string',
            'traininglink' => 'required|string',
            'adminref' => 'required|string'
        ]);
        $trainnglist = SysTraining::select('id', 'training_name', 'training_link')->where('id', $request->id)->first();
        $oldname = $trainnglist->training_name;
        $trainnglist->training_name = $request->trainingname;
        $trainnglist->training_link = $request->traininglink;
        $trainnglist->save();
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'Mandatory Training',
            'log_details' => "Updated Mandatory Training {$oldname} set the new name to {$request->trainingname} and the new link to {$request->traininglink}."
        ]);
        return response()->json([
            'success' => true,
            'message' => "Mandatory Training {$request->trainingname} Updated Successfully."
        ]);
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'adminref' => 'required|string'
        ]);
        $trainnglist = SysTraining::select('id', 'training_name', 'training_link')->where('id', $request->id)->first();
        $oldname = $trainnglist->training_name;
        $trainnglist->deleted = 1;
        $trainnglist->save();
        SysAdminLogs::create([
            'refrence' => $request->adminref,
            'log_action' => 'Mandatory Training',
            'log_details' => "Deleted Mandatory Training {$oldname}."
        ]);
        return response()->json([
            'success' => true,
            'message' => "Mandatory Training {$oldname} Deleted Successfully."
        ]);
    }
}
