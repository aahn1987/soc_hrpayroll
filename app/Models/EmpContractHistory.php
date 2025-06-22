<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpContractHistory extends Model
{
    protected $table = 'emp_contracthistory';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'soc_reference',
        'hub_id',
        'duty_station_id',
        'contract_id',
        'job_position',
        'division_id',
        'staff_grouping_id',
        'programme_id',
        'grade_id',
        'employee_step',
        'working_hours',
        'first_employement_date',
        'soc_employemrnt_date',
        'contract_start',
        'contract_end',
        'eligible_for_upgrade',
        'contract_category_id',
        'contract_remarks',
        'is_current',
        'deleted',
    ];

}
