<?php

namespace App\Http\Controllers;
use App\Services\FcmService;
use App\Models\EmpTokens;
use App\Models\DataEvaluationSchedule;
use Illuminate\Http\Request;

class DataEvaluationScheduleController extends Controller
{
    public function newsched(Request $request)
    {
        DataEvaluationSchedule::where('is_current', 1)->update(['is_current' => 0]);
        DataEvaluationSchedule::create([
            'schedule_month' => $request->month,
            'schedule_year' => $request->year,
            'is_current' => 1,
        ]);
        $title = 'New Evaluation Schedule';
        $body = "Evaluation schedule for {$request->month} - {$request->year} is ready please do your evaluation";
        $tokens = EmpTokens::pluck('fcm_token')->toArray();
        $chunks = array_chunk($tokens, 500);
        foreach ($chunks as $chunk) {
            FcmService::sendNotificationBatch($chunk, $title, $body);
        }
        return response()->json([
            'success' => true,
            'message' => "Schedule updated successfully for {$request->month} - {$request->year}"
        ]);
    }
}
