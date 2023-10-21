<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public function eduClass()
    {
        return $this->belongsTo(EduClasses::class, 'class_id');
    }
    public function version()
    {
        return $this->belongsTo(EduVersions::class, 'version_id');
    }
}
