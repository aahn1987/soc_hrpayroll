<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpLeaveRequests extends Model
{
    protected $table = 'emp_leaverequest';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'leave_token',
        'soc_reference',
        'leave_type_id',
        'start_date',
        'end_date',
        'leave_reason',
        'days',
        'supervisor_name',
        'supervisor_email',
        'supervisor_acceptance',
        'with_head_of_sub_office',
        'head_of_sub_office_name',
        'head_of_sub_office_email',
        'head_of_sub_office_acceptance',
        'with_report',
        'report_url',
        'leave_canceled',
        'cancellation_date',
        'supervisor_cancellation',
        'head_of_sub_office_cancellation',
        'supervisor_remarks',
        'head_of_sub_office_remarks',
        'deleted',
    ];

    protected $casts = [
        'days' => 'double',
        'supervisor_acceptance' => 'boolean',
        'with_head_of_sub_office' => 'boolean',
        'head_of_sub_office_acceptance' => 'boolean',
        'with_report' => 'boolean',
        'leave_canceled' => 'boolean',
        'supervisor_cancellation' => 'boolean',
        'head_of_sub_office_cancellation' => 'boolean',
        'deleted' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'cancellation_date' => 'date',
    ];
}
