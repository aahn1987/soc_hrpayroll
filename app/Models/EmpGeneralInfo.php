<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpGeneralInfo extends Model
{
    protected $table = 'emp_generalinfo';
    public $timestamps = true;
    protected $fillable = [
        'soc_reference',
        'fullname',
        'profilepicture',
        'gender',
        'marital_status',
        'no_of_dependants',
        'blood_group',
        'date_of_birth',
        'nationality',
        'emailaddress',
        'phonenumber',
        'homeaddress',
        'employee_status',
        'deleted',
    ];
}
