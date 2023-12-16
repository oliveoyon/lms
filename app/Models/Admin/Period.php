<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    public function classRoutine()
    {
        return $this->belongsTo(ClassRoutines::class);
    }
}
