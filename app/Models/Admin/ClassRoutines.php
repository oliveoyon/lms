<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoutines extends Model
{
    use HasFactory;
    public function version() {
        return $this->belongsTo(EduVersions::class, 'version_id');
    }

    public function eduClass() {
        return $this->belongsTo(EduClasses::class, 'class_id');
    }

    public function section() {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }
}
