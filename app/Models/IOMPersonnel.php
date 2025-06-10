<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IOMPersonnel extends Model
{
    protected $table = 'iom_personnel';

    public $timestamps = true;

    protected $fillable = [
        'refrence',
        'fullname',
        'emailaddress',
        'phonenumber',
        'profileimage',
        'servicestatus',
        'deleted',
    ];
}
