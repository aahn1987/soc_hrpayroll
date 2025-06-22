<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpGeneralInfo extends Model
{
    protected $table = 'emp_generalinfo';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'employee_reference',
        'soc_reference',
        'fullname',
        'profilepicture',
        'gender',
        'marital_status',
        'no_of_dependants',
        'blood_group',
        'date_of_birth',
        'age',
        'nationality',
        'emailaddress',
        'phonenumber',
        'homeaddress',
        'employee_status',
        'deleted',
    ];

    protected $casts = [
        'gender' => 'integer',
        'marital_status' => 'integer',
        'no_of_dependants' => 'integer',
        'age' => 'integer',
        'employee_status' => 'boolean',
        'deleted' => 'boolean',
        'date_of_birth' => 'date',
    ];
}
