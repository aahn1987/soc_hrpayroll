<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpEvaluated extends Model
{
    protected $table = 'emp_evaluated';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'schedule_id',
        'soc_reference',
        'eval_token',
        'evaluation_requested',
        'deleted',
    ];

    protected $casts = [
        'evaluation_requested' => 'boolean',
        'deleted' => 'boolean',
    ];
}
