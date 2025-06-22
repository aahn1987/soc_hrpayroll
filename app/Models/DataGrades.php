<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataGrades extends Model
{
    protected $table = 'data_grades';
    protected $fillable = ['grade_equivalent', 'normalized'];
}
