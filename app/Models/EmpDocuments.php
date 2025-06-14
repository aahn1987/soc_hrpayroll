<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpDocuments extends Model
{
    protected $table = 'emp_documents';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'soc_reference',
        'file_token',
        'filename',
        'file_type',
        'file_viewer',
        'file_icon',
        'file_url',
        'file_path',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];
}
