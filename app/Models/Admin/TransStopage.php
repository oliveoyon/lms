<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransStopage extends Model
{
    use HasFactory;

    protected $fillable = [
        'stopage_name',
        'stopage_type',
        'distance',
        'stopage_description',
        'stopage_status',
    ];
}
