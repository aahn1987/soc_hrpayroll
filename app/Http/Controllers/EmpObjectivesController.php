<?php

namespace App\Http\Controllers;

use App\Models\EmpObjectives;
use Illuminate\Http\Request;

class EmpObjectivesController extends Controller
{
    public function creatobjective($obj = [])
    {
        $fields = [
            'soc_reference',
            'objective_text',
            'objective_token',
        ];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $obj[$field];
        }
        EmpObjectives::create($data);
    }
}
