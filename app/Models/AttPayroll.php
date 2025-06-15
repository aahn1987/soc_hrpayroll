<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttPayroll extends Model
{
    protected $table = 'att_payroll';
    protected $fillable = [
        'soc_reference',
        'pay_month',
        'pay_year',
        'import_date',
        'base_salary',
        'communication',
        'attendance',
        'dpta',
        'retors',
        'soc_fees',
        'total_salary',
        'net_salary',
        'general_liabilty',
        'occupational_insurance_amount',
        'health_insurance_by_company',
        'health_insurance_by_employee',
        'social_security_by_company',
        'social_security_by_employee',
        'personal_income_tax',
        'rnr',
        'extrafees',
        'final_net_salary',
        'salary_paid_by_company',
        'estimated_salary',
        'expectation_gap',
        'acions',
        'deleted',
    ];
}
