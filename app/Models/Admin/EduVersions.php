<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EduVersions extends Model
{
    use HasFactory;

    public function classes()
    {
        return $this->hasMany(EduClasses::class, 'version_id');
    }
}
