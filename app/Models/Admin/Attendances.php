<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;
    protected $fillable = [
        'attendance_hash_id',
        'std_id',
        'class_id',
        'section_id',
        'roll_no',
        'academic_year',
        'attendance',
        'attendance_date',
        'month',
        'fine_clearance',
        'school_id',
    ];
}
