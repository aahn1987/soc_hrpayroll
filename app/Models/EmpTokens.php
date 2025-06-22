<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpTokens extends Model
{
    protected $table = 'emp_tokens';
    protected $fillable = ['soc_reference', 'fcm_token'];
}
