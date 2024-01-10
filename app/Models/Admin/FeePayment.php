<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'fee_collection_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'status',
        'school_id',
    ];

    public function feeCollection()
    {
        return $this->belongsTo(FeeCollection::class, 'fee_collection_id', 'id');
    }
}
