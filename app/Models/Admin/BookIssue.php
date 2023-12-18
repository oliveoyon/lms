<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookIssue extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'book_id',
        'quantity',
        'issue_date',
        'due_date',
        'return_date',
        'fine_amount',
        'issue_status',
        // Add other fields as needed
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
