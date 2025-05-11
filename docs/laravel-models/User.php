<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the borrow history for the user.
     */
    public function borrowHistory()
    {
        return $this->hasMany(BorrowHistory::class);
    }

    /**
     * Get the cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the payments for the user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->user_type === 0;
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    /**
     * Get active cart items for the user.
     */
    public function activeCartItems()
    {
        return $this->cartItems()->where('status', 0);
    }

    /**
     * Get active borrow requests for the user.
     */
    public function activeBorrowRequests()
    {
        return $this->borrowHistory()->where('status', 'Requested');
    }

    /**
     * Get currently borrowed books for the user.
     */
    public function currentlyBorrowedBooks()
    {
        return $this->borrowHistory()->where('status', 'Issued');
    }

    /**
     * Get completed payments for the user.
     */
    public function completedPayments()
    {
        return $this->payments()->where('payment_status', 'Completed');
    }

    /**
     * Scope a query to only include regular users.
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('user_type', 1);
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdminUsers($query)
    {
        return $query->where('user_type', 0);
    }
}