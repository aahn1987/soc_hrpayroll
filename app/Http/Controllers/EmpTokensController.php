<?php

namespace App\Http\Controllers;

use App\Models\EmpTokens;
use Illuminate\Http\Request;

class EmpTokensController extends Controller
{
    public function store(Request $request)
    {
        EmpTokens::updateOrCreate(
            ['fcm_token' => $request->fcm_token],
            ['soc_reference' => $request->soc_reference]
        );
    }
    public function destroy(Request $request)
    {
        EmpTokens::where('fcm_token', $request->fcm_token)->delete();
    }
}
