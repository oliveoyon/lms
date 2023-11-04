<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicFeeAmount extends Model
{
    use HasFactory;
    public function academicFeeGroup()
    {
        return $this->belongsTo(AcademicFeeGroup::class, 'aca_group_id');
    }

    public function academicFeeHead()
    {
        return $this->belongsTo(AcademicFeeHead::class, 'aca_feehead_id');
    }

    public function eduClass()
    {
        return $this->belongsTo(EduClasses::class, 'class_id');
    }

    
    
}
