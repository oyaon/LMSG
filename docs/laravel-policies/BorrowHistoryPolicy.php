<?php

namespace App\Policies;

use App\Models\BorrowHistory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BorrowHistoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admins can view all borrow history
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BorrowHistory $borrowHistory): bool
    {
        // Users can view their own borrow history, admins can view any
        return $user->id === $borrowHistory->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create a borrow request
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BorrowHistory $borrowHistory): bool
    {
        // Only admins can update borrow history (approve/decline)
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BorrowHistory $borrowHistory): bool
    {
        // Users can delete their own pending requests, admins can delete any
        return ($user->id === $borrowHistory->user_id && $borrowHistory->status === 'Requested') 
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can return a borrowed book.
     */
    public function return(User $user, BorrowHistory $borrowHistory): bool
    {
        // Users can only return their own borrowed books
        return $user->id === $borrowHistory->user_id && $borrowHistory->status === 'Issued';
    }
}