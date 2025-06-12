<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysUsersLogs extends Model
{
    protected $table = 'sys_userlogs';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'soc_reference',
        'log_date',
        'log_action',
        'log_details',
    ];
    protected $casts = [
        'log_date' => 'datetime',
    ];
}
