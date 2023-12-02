<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'std_hash_id',
        'std_id',
        'std_name',
        'std_name_bn',
        'academic_year',
        'version_id',
        'class_id',
        'section_id',
        'admission_date',
        'std_phone',
        'std_phone1',
        'std_fname',
        'std_mname',
        'std_dob',
        'std_gender',
        'std_email',
        'blood_group',
        'std_present_address',
        'std_permanent_address',
        'std_f_occupation',
        'std_m_occupation',
        'f_yearly_income',
        'std_gurdian_name',
        'std_gurdian_relation',
        'std_gurdian_mobile',
        'std_gurdian_address',
        'std_picture',
        'std_category',
        'std_status',
        'school_id',
    ];
}
