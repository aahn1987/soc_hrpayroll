<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpLeaveBalance extends Model
{
    protected $table = 'emp_leavebalance';
    protected $fillable = [
        'soc_reference',
        'annual_leave_balance',
        'sick_leave_balance',
        'carried_forward_balance'
    ];
}
