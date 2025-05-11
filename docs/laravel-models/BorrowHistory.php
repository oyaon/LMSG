<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'borrow_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_email',
        'book_id',
        'issue_date',
        'fine',
        'status',
        'return_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Get the user that owns the borrow history.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that is borrowed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Scope a query to only include requested borrows.
     */
    public function scopeRequested($query)
    {
        return $query->where('status', 'Requested');
    }

    /**
     * Scope a query to only include issued borrows.
     */
    public function scopeIssued($query)
    {
        return $query->where('status', 'Issued');
    }

    /**
     * Scope a query to only include returned borrows.
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'Returned');
    }

    /**
     * Scope a query to only include declined borrows.
     */
    public function scopeDeclined($query)
    {
        return $query->where('status', 'Declined');
    }

    /**
     * Calculate fine for overdue books.
     */
    public function calculateFine()
    {
        if ($this->status !== 'Issued' || !$this->issue_date || !$this->return_date) {
            return 0;
        }

        $daysOverdue = now()->diffInDays($this->return_date, false);
        
        if ($daysOverdue <= 0) {
            return 0;
        }

        // $10 per day overdue
        return $daysOverdue * 10;
    }
}