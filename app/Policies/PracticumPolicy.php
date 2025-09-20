<?php

namespace App\Policies;

use App\Models\Practicum;
use App\Models\User;

class PracticumPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return $user->can('practicum.view');
    }

    public function create(User $user)
    {
        return $user->can('practicum.add');
    }

    public function delete(User $user, Practicum $practicum)
    {
        return $user->can('practicum.delete') &&
            $practicum->staff()->where('user_id', $user->id)->exists();
    }

    public function view(User $user, Practicum $practicum)
    {
        if ($user->can('practicum.view')) {
            return true;
        }

        if ($user->hasRole('student')) {
            return $user->enrollments()
                ->where('practicum_id', $practicum->id)
                ->where('status', 'APPROVED')
                ->exists();
        }

        return false;
    }

    public function calculateScore(User $user, Practicum $practicum)
    {
        return $user->can('practicum.calculate_scores') &&
            $practicum->staff()->where('user_id', $user->id)->exists();
    }
}
