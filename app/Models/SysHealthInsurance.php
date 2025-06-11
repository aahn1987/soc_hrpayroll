<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysHealthInsurance extends Model
{
    protected $table = 'sys_healthinsurance';
    protected $fillable = [
        'language',
        'text',
        'video_url'
    ];
}
