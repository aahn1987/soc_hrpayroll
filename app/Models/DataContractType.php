<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataContractType extends Model
{
    protected $table = 'data_contracttype';
    protected $fillable = ['contract_type', 'normalized'];
}
