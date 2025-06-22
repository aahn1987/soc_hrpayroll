<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysLoginData extends Model
{
    protected $table = "sys_logindata";
    protected $fillable = [
        'username',
        'userpass',
        'userrole',
        'user_reference'
    ];
    protected $hidden = [
        'userpass'
    ];
}
