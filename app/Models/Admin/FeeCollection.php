<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCollection extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'academic_fee_group_id',
        'payable_amount',
        'amount_paid',
        'is_paid',
        'due_date',
        'fee_description',
    ];
}
