<?php

namespace App\Policies;

use App\Models\Borrowing;
use App\Models\User;

class BorrowingPolicy
{
    /**
     * Determine if the user owns the borrowing.
     */
    public function own(User $user, Borrowing $borrowing): bool
    {
        return $user->id === $borrowing->user_id;
    }

    /**
     * Allow viewing receipt for owner, admin, or pustakawan.
     */
    public function view(User $user, Borrowing $borrowing): bool
    {
        // Owner
        if ($user->id === $borrowing->user_id) return true;
        // Admin
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) return true;
        // Pustakawan
        if (method_exists($user, 'isLibrarian') && $user->isLibrarian()) return true;
        return false;
    }
}
