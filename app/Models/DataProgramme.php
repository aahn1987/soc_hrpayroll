<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataProgramme extends Model
{
    protected $table = 'data_programme';
    protected $fillable = ['programme', 'normalized'];
}
