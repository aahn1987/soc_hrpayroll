<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysConfigs extends Model
{
    protected $table = 'sys_configs';
    protected $fillable = [
        'config_value',
    ];
}
