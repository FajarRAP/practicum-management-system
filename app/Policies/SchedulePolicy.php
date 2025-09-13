<?php

namespace App\Policies;

use App\Models\User;

class SchedulePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        // Only assistants and lecturers can create schedule
        return $user->hasAnyRole(['assistant', 'lecturer']);
    }

    public function update(User $user): bool
    {
        // Only assistants and lecturers can update schedule
        return $user->hasAnyRole(['assistant', 'lecturer']);
    }

    public function delete(User $user): bool
    {
        // Only assistants and lecturers can delete schedule
        return $user->hasAnyRole(['assistant', 'lecturer']);
    }

    public function approve(User $user): bool
    {
        // Only lab tech can approve schedule
        return $user->hasRole('lab_tech');
    }

    public function reject(User $user): bool
    {
        // Only lab tech can reject schedule
        return $user->hasRole('lab_tech');
    }
}
