<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysHealthInsuranceFiles extends Model
{
    protected $table = 'sys_healthinsurance_files';
    protected $fillable = [
        'filename',
        'langauge',
        'filelink'
    ];
}
