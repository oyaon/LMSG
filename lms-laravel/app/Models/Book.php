<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'all_books';
    protected $fillable = [
        'name',
        'author_id',
        'author',
        'category',
        'description',
        'quantity',
        'price',
        'pdf',
        'cover_image',
    ];
}
