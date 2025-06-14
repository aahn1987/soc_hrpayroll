<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDutyStation extends Model
{
    protected $table = 'data_dutystations';
    protected $fillable = ['duty_station', 'normalized'];
}
