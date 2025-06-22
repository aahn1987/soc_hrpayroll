<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDivison extends Model
{
    protected $table = 'data_division';
    protected $fillable = ['division', 'normalized'];
}
