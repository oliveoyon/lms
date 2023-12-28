<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCollection extends Model
{
    use HasFactory;
    protected $fillable = [
        'fee_collection_hash_id',
        'std_id',
        'fee_group_id',
        'aca_feehead_id',
        'aca_feeamount_id',
        'payable_amount',
        'is_paid',
        'due_date',
        'fee_description',
        'fee_collection_status',
        'academic_year',
        'school_id',
    ];

}
