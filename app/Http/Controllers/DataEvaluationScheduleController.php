<?php

namespace App\Http\Controllers;
use App\Services\FcmService;
use App\Models\EmpTokens;
use App\Models\DataEvaluationSchedule;
use Illuminate\Support\Carbon;
class DataEvaluationScheduleController extends Controller
{
    public function run()
    {
        $today = Carbon::now();
        if (!($today->day === 5 && in_array($today->month, [1, 6]))) {
            return response()->json([
                'success' => false,
                'message' => 'Skipped: Today is not 5th Jan or 5th Jun'
            ]);
        }
        DataEvaluationSchedule::where('is_current', 1)->update(['is_current' => 0]);
        $monthCode = $today->month === 1 ? 'Jan' : 'Jun';
        $new = DataEvaluationSchedule::create([
            'schedule_month' => $monthCode,
            'schedule_year' => $today->year,
            'is_current' => 1,
        ]);
        $title = 'New Evaluation Schedule';
        $body = "Evaluation schedule for $monthCode - {$today->year} is ready please do your evaluation";
        $tokens = EmpTokens::pluck('fcm_token')->toArray();

        foreach ($tokens as $token) {
            FcmService::sendNotification($token, $title, $body);
        }
        return response()->json([
            'success' => true,
            'message' => "Schedule updated successfully for {$monthCode} - {$today->year}"
        ]);
    }
}
