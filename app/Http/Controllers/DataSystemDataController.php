<?php

namespace App\Http\Controllers;

use App\Models\DataContractCategory;
use App\Models\DataContractType;
use App\Models\DataDangerPay;
use App\Models\DataDivison;
use App\Models\DataDutyStation;
use App\Models\DataGenders;
use App\Models\DataGrades;
use App\Models\DataHubs;
use App\Models\DataLeaveTypes;
use App\Models\DataMaritalStatus;
use App\Models\DataPaymentTypes;
use App\Models\DataProgramme;
use App\Models\DataStaffGrouping;
use App\Models\DataYesNoTable;

class DataSystemDataController extends Controller
{
    public function contractcategories()
    {
        $DataContractCategory = DataContractCategory::select('id', 'contract_category')->where('deleted', 0)->get();
        return response()->json($DataContractCategory);
    }
    public function contracttype()
    {
        $DataContractType = DataContractType::select('id', 'contract_type')->where('deleted', 0)->get();
        return response()->json($DataContractType);
    }
    public function dangerpay()
    {
        $DataDangerPay = DataDangerPay::select('id', 'danger_pay_type')->where('deleted', 0)->get();
        return response()->json($DataDangerPay);
    }
    public function divison()
    {
        $DataDivison = DataDivison::select('id', 'division')->where('deleted', 0)->get();
        return response()->json($DataDivison);
    }
    public function dutystation()
    {
        $DataDutyStation = DataDutyStation::select('id', 'duty_station')->where('deleted', 0)->get();
        return response()->json($DataDutyStation);
    }
    public function gender()
    {
        $DataGenders = DataGenders::select('id', 'gender_type')->where('deleted', 0)->get();
        return response()->json($DataGenders);
    }
    public function grade()
    {
        $DataGrades = DataGrades::select('id', 'grade_equivalent')->where('deleted', 0)->get();
        return response()->json($DataGrades);
    }
    public function hub()
    {
        $DataHubs = DataHubs::select('id', 'hub_name')->where('deleted', 0)->get();
        return response()->json($DataHubs);
    }
    public function leavetype()
    {
        $DataLeaveTypes = DataLeaveTypes::select('id', 'leave_type')->where('deleted', 0)->get();
        return response()->json($DataLeaveTypes);
    }
    public function maritalstatus()
    {
        $DataMaritalStatus = DataMaritalStatus::select('id', 'martial_statue_type')->where('deleted', 0)->get();
        return response()->json($DataMaritalStatus);
    }
    public function paymenttype()
    {
        $DataPaymentTypes = DataPaymentTypes::select('id', 'payment_type')->where('deleted', 0)->get();
        return response()->json($DataPaymentTypes);
    }
    public function programme()
    {
        $DataProgramme = DataProgramme::select('id', 'programme')->where('deleted', 0)->get();
        return response()->json($DataProgramme);
    }
    public function staffgrouping()
    {
        $DataStaffGrouping = DataStaffGrouping::select('id', 'staff_grouping')->where('deleted', 0)->get();
        return response()->json($DataStaffGrouping);
    }
    public function yesno()
    {
        $DataYesNoTable = DataYesNoTable::select('id', 'yes_or_no')->where('deleted', 0)->get();
        return response()->json($DataYesNoTable);
    }
    public function contractcategoriesid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataContractCategory::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);

        if ($matched) {
            return $matched->id;
        }
        $new = new DataContractCategory();
        $new->contract_category = $originalName;
        $new->normalized = $normalizedName;
        $new->deleted = 0;
        $new->save();
        return $new->id;
    }
    public function contracttypeid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataContractType::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);

        if ($matched) {
            return $matched->id;
        }
        $new = new DataContractType();
        $new->contract_type = $originalName;
        $new->normalized = $normalizedName;
        $new->deleted = 0;
        $new->save();
        return $new->id;
    }
    public function dangerpayid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataDangerPay::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);
        if ($matched) {
            return $matched->id;
        }
        return 1;
    }
    public function divisonid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataDivison::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);

        if ($matched) {
            return $matched->id;
        }
        $new = new DataDivison();
        $new->division = $originalName;
        $new->normalized = $normalizedName;
        $new->deleted = 0;
        $new->save();
        return $new->id;
    }
    public function dutystationid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataDutyStation::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);

        if ($matched) {
            return $matched->id;
        }
        $new = new DataDutyStation();
        $new->duty_station = $originalName;
        $new->normalized = $normalizedName;
        $new->deleted = 0;
        $new->save();
        return $new->id;
    }
    public function genderid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataDutyStation::where('deleted', 0)->where('normalized', $normalizedName)->get();
        return $existing->id;
    }
    public function gradeid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataGrades::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);

        if ($matched) {
            return $matched->id;
        }
        $new = new DataGrades();
        $new->grade_equivalent = $originalName;
        $new->normalized = $normalizedName;
        $new->deleted = 0;
        $new->save();
        return $new->id;
    }
    public function hubid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataHubs::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);

        if ($matched) {
            return $matched->id;
        }
        $new = new DataHubs();
        $new->hub_name = $originalName;
        $new->normalized = $normalizedName;
        $new->deleted = 0;
        $new->save();
        return $new->id;
    }
    public function maritalstatusid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataMaritalStatus::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);
        if ($matched) {
            return $matched->id;
        }
        return 1;
    }
    public function programmeid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataProgramme::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);

        if ($matched) {
            return $matched->id;
        }
        $new = new DataProgramme();
        $new->programme = $originalName;
        $new->normalized = $normalizedName;
        $new->deleted = 0;
        $new->save();
        return $new->id;
    }
    public function staffgroupingid($item)
    {
        $originalName = $item;
        $normalizedName = clear_string($originalName);
        $existing = DataStaffGrouping::where('deleted', 0)->where('normalized', $normalizedName)->get();
        $matched = $existing->firstWhere('normalized', $normalizedName);

        if ($matched) {
            return $matched->id;
        }
        $new = new DataStaffGrouping();
        $new->staff_grouping = $originalName;
        $new->normalized = $normalizedName;
        $new->deleted = 0;
        $new->save();
        return $new->id;
    }
    public function requestleavetype($item)
    {
        $DataLeaveTypes = DataLeaveTypes::select('leave_type')->where('id', $item)->first();
        return $DataLeaveTypes['leave_type'];
    }
}
