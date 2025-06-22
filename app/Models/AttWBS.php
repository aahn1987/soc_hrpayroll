<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttWBS extends Model
{
    protected $table = 'att_wbs';
    protected $fillable = [
        'soc_reference',
        'pay_month',
        'pay_year',
        'import_date',
        'wbs1_wbl_for_salaries',
        'percentage1',
        'allocation1',
        'comment_wbs1',
        'wbs2_wbl_for_salaries',
        'percentage2',
        'allocation2',
        'comment_wbs2',
        'wbs3_wbl_for_salaries',
        'percentage3',
        'allocation3',
        'comment_wbs3',
        'wbs4_wbl_for_salaries',
        'percentage4',
        'allocation4',
        'comment_wbs4',
        'wbs5_wbl_for_salaries',
        'percentage5',
        'allocation5',
        'comment_wbs5',
        'wbs6_wbl_for_salaries',
        'percentage6',
        'allocation6',
        'comment_wbs6',
        'wbs7_wbl_for_salaries',
        'percentage7',
        'allocation7',
        'comment_wbs7',
        'wbs8_wbl_for_salaries',
        'percentage8',
        'allocation8',
        'comment_wbs8',
        'total_wbs',
        'deleted',
    ];

}
