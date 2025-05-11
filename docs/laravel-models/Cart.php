<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cart';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_email',
        'book_id',
        'date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book in the cart.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Scope a query to only include active cart items.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Scope a query to only include purchased cart items.
     */
    public function scopePurchased($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get total price of cart items for a user.
     */
    public static function getTotalPrice($userId)
    {
        return self::where('user_id', $userId)
            ->where('status', 0)
            ->join('books', 'cart.book_id', '=', 'books.id')
            ->sum('books.price');
    }
}