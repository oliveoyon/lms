<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class AcademicStudent extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'std_hash_id',
        'std_id',
        'version_id',
        'class_id',
        'section_id',
        'roll_no',
        'academic_year',
        'password',
        'st_aca_status',
        'school_id',
        // Add other fields that you want to be mass assignable...
    ];



    public function student()
    {
        return $this->belongsTo(Student::class, 'std_id', 'std_id');
    }
}
