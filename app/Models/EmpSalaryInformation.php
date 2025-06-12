<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpSalaryInformation extends Model
{
    protected $table = 'emp_salaryinformation';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'soc_reference',
        'basesalary',
        'danger_pay',
        'general_liabilty',
        'occupational_insurance',
        'occupational_insurance_amount',
        'health_isurance',
        'health_insurance_by_company',
        'health_insurance_by_employee',
        'social_security',
        'social_security_by_company',
        'social_security_by_employee',
        'personal_income_tax',
        'extra_fees',
        'rnr',
        'payment_type_id',
        'bankname',
        'account_number_p1',
        'account_number_p2',
        'account_number_p3',
        'deleted',
    ];

    protected $casts = [
        'basesalary' => 'double',
        'danger_pay' => 'integer',
        'general_liabilty' => 'double',
        'occupational_insurance' => 'integer',
        'occupational_insurance_amount' => 'double',
        'health_isurance' => 'integer',
        'health_insurance_by_company' => 'double',
        'health_insurance_by_employee' => 'double',
        'social_security' => 'integer',
        'social_security_by_company' => 'double',
        'social_security_by_employee' => 'double',
        'personal_income_tax' => 'double',
        'extra_fees' => 'double',
        'rnr' => 'double',
        'payment_type_id' => 'integer',
        'deleted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
