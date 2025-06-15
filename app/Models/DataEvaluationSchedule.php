<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataEvaluationSchedule extends Model
{
    protected $table = 'data_evaluation_schedule';

    protected $fillable = [
        'schedule_month',
        'schedule_year',
        'is_current',
        'deleted',
    ];
}
