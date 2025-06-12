<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpJobInformation extends Model
{
    protected $table = 'emp_jobinformation';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'soc_reference',
        'svn_hired',
        'employee_link',
        'supervisor_name',
        'supervisor_email',
        'head_of_sub_office_name',
        'head_of_sub_office_email',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];
}
