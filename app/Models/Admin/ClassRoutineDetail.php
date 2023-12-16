<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoutineDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'class_routine_id',
        'day_of_week',
        'period_id',
        'subject_id',
    ];
    public function classRoutine()
    {
        return $this->belongsTo(ClassRoutines::class, 'class_routine_id');
    }

    // Define an indirect relationship to EduClasses through ClassRoutines
    public function eduClass()
    {
        return $this->belongsTo(EduClasses::class, 'class_routine_id', 'id')
                    ->via('classRoutine', function ($query) {
                        // Add any additional conditions if needed
                    });
    }
    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
