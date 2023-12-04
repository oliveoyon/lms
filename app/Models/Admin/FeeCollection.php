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
        'amount_paid',
        'is_paid',
        'paid_date',
        'due_date',
        'fee_description',
        'academic_year',
        'fee_collection_status',
        'school_id',
    ];
}
