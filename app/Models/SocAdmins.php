<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocAdmins extends Model
{
    protected $table = 'soc_admins';
    public $timestamps = true;
    protected $fillable = [
        'refrence',
        'fullname',
        'jobpostition',
        'emailaddress',
        'phonenumber',
        'profileimage',
        'servicestatus',
        'deleted',
    ];

}
