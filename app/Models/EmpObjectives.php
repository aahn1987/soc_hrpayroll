<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpObjectives extends Model
{
    protected $table = 'emp_objective';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'soc_reference',
        'supervisor_name',
        'supervisor_email',
        'supervisor_approval',
        'with_head_of_sub_office',
        'head_of_sub_office_name',
        'head_of_sub_office_email',
        'head_of_sub_office_approval',
        'objective_text',
        'objective_token',
        'deleted',
    ];

    protected $casts = [
        'editable' => 'boolean',
        'supervisor_approval' => 'boolean',
        'with_head_of_sub_office' => 'boolean',
        'head_of_sub_office_approval' => 'boolean',
        'deleted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
