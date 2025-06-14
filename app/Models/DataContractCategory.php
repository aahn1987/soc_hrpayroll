<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataContractCategory extends Model
{
    protected $table = 'data_contractcategories';
    protected $fillable = ['contract_category', 'normalized'];
}
