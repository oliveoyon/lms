<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignTeacher extends Model
{
    use HasFactory;
    protected $table = 'assign_teachers';
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function version()
    {
        return $this->belongsTo(EduVersions::class, 'version_id');
    }

    public function eduClass()
    {
        return $this->belongsTo(EduClasses::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
