<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysTraining extends Model
{
    protected $table = 'sys_training';
    protected $fillable = [
        'training_name',
        'training_link',
        'deleted'
    ];
}

