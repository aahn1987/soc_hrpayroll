<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysAdminLogs extends Model
{
    protected $table = 'sys_adminslog';
    protected $fillable = [
        'refrence',
        'log_date',
        'log_action',
        'log_details',
    ];

    public $timestamps = false;

    protected $casts = [
        'log_date' => 'datetime',
    ];
}
