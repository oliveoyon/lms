<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function version()
    {
        return $this->belongsTo(EduVersions::class, 'version_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(EduClasses::class, 'class_id', 'id');
    }


}
