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
}
