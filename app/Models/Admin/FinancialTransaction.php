<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_hash_id',
        'user_id',
        'transaction_type',
        'related_id',
        'account_category',
        'amount',
        'transaction_date',
        'description',
    ];
}
