<?php

namespace App\Http\Controllers;
use App\Http\Controllers\DataSystemDataController;
use App\Models\DataDutyStation;
use App\Models\AttWBS;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function listds()
    {
        $dslister = new DataSystemDataController;
        $results = $dslister->dutystation();
        return $results;
    }
    public function storeWbs(Request $request, array $fallback = [])
    {
        // قائمة الحقول المطلوبة
        $fields = [
            'soc_reference',
            'pay_month',
            'pay_year',
            'import_date',
            'wbs1_wbl_for_salaries',
            'percentage1',
            'allocation1',
            'comment_wbs1',
            'wbs2_wbl_for_salaries',
            'percentage2',
            'allocation2',
            'comment_wbs2',
            'wbs3_wbl_for_salaries',
            'percentage3',
            'allocation3',
            'comment_wbs3',
            'wbs4_wbl_for_salaries',
            'percentage4',
            'allocation4',
            'comment_wbs4',
            'wbs5_wbl_for_salaries',
            'percentage5',
            'allocation5',
            'comment_wbs5',
            'wbs6_wbl_for_salaries',
            'percentage6',
            'allocation6',
            'comment_wbs6',
            'wbs7_wbl_for_salaries',
            'percentage7',
            'allocation7',
            'comment_wbs7',
            'wbs8_wbl_for_salaries',
            'percentage8',
            'allocation8',
            'comment_wbs8',
            'total_wbs'
        ];

        // بناء البيانات من request أو fallback
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->filled($field) ? $request->input($field) : ($fallback[$field] ?? null);
        }

        // إنشاء السجل
        $created = AttWbs::create($data);

        return response()->json([
            'success' => true,
            'data' => $created,
            'message' => 'WBS record created successfully.',
        ]);
    }
    public function getOrCreateDutyStationId(Request $request)
    {
        $request->validate([
            'duty_station' => 'required|string'
        ]);

        $originalName = $request->duty_station;
        $normalizedName = clear_string($originalName);
        $existing = DataDutyStation::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);

        if ($matched) {
            return $matched->id;
        }
        return 1;

    }
}
