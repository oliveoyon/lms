<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'book_hash_id',
        'book_cat_id',
        'book_title',
        'author',
        'isbn',
        'edition',
        'publisher',
        'shelf',
        'position',
        'book_purchase_date',
        'cost',
        'no_of_copy',
        'availability',
        'language',
        'book_status',
        'school_id',
    ];

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }
    

}
