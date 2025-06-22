<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataStaffGrouping extends Model
{
    protected $table = 'data_staffgrouping';
    protected $fillable = ['staff_grouping', 'normalized'];
}
