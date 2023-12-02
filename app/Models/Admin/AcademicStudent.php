<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicStudent extends Model
{
    use HasFactory;
    protected $fillable = [
        'std_hash_id',
        'std_id',
        'version_id',
        'class_id',
        'section_id',
        'roll_no',
        'academic_year',
        'std_password',
        'st_aca_status',
        'school_id',
        // Add other fields that you want to be mass assignable...
    ];
}
