<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * Get the author that owns the book.
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the borrow history for the book.
     */
    public function borrowHistory()
    {
        return $this->hasMany(BorrowHistory::class);
    }

    /**
     * Get the cart items for the book.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the cover image URL attribute.
     */
    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return asset('images/default-book-cover.jpg');
        }
        
        return asset('storage/covers/' . $this->cover_image);
    }

    /**
     * Get the PDF URL attribute.
     */
    public function getPdfUrlAttribute()
    {
        if (!$this->pdf) {
            return null;
        }
        
        return asset('storage/pdfs/' . $this->pdf);
    }

    /**
     * Scope a query to search books.
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('author', 'like', "%{$searchTerm}%")
            ->orWhereHas('author', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get all available categories.
     */
    public static function getCategories()
    {
        return self::select('category')->distinct()->orderBy('category')->pluck('category');
    }
}