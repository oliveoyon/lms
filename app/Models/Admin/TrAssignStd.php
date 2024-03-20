<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrAssignStd extends Model
{
    use HasFactory;
    protected $fillable = [
        'tr_assign_hash_id',
        'std_id',
        'route_id',
        'pickup_stopage',
        'drop_stopage',
        'pickup_time',
        'drop_time',
        'academic_year',
        'tr_assign_status',
        'school_id',
    ];
}
