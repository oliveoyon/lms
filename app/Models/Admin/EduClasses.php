<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EduClasses extends Model
{
    use HasFactory;

    public function version()
    {
        return $this->belongsTo(EduVersions::class, 'version_id');
    }
}
