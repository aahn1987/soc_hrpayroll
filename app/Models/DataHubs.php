<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataHubs extends Model
{
    protected $table = 'data_hubs';
    protected $fillable = ['hub_name', 'normalized'];
}
