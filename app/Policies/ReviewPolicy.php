<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Review $review): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->role === 'admin' || $user->id === $review->user_id;
    }

    public function approve(User $user, Review $review): bool
    {
        return $user->role === 'admin';
    }
}