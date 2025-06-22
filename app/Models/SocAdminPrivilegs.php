<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocAdminPrivilegs extends Model
{
    protected $table = 'soc_admin_privileges';
    public $timestamps = true;
    protected $fillable = [
        'refrence',
        'manage_staff',
        'manage_iom',
        'manage_employees',
        'manage_payroll',
        'manage_leaves',
        'manage_evalutions',
        'manage_sysconfig',
        'deleted',
    ];
}
