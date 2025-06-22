<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpEvaluations extends Model
{
    protected $table = 'emp_evaluations';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'eval_token',
        'request_date',
        'request_for_month',
        'request_for_year',
        'soc_reference',
        'objective_text',
        'contract_duration',
        'employee_rating',
        'supervisor_name',
        'supervisor_email',
        'supervisor_comment',
        'supervisor_eval_date',
        'supervisor_status',
        'head_of_sub_office_name',
        'head_of_sub_office_email',
        'head_of_sub_office_comment',
        'head_of_sub_office_eval_date',
        'head_of_sub_office_status',
        'employee_agreement',
        'employee_comment',
        'employee_comment_date',
        'employee_status',
        'progress',
        'deleted',
    ];

    protected $casts = [
        'supervisor_status' => 'boolean',
        'head_of_sub_office_status' => 'boolean',
        'employee_status' => 'boolean',
        'deleted' => 'boolean',
        'progress' => 'double',
        'request_date' => 'date',
        'supervisor_eval_date' => 'date',
        'head_of_sub_office_eval_date' => 'date',
        'employee_comment_date' => 'date',
    ];
}
