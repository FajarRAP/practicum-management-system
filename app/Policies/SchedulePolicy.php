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
        return $user->can('schedule.add');
    }

    public function update(User $user): bool
    {
        // Only assistants and lecturers can update schedule
        return $user->can('schedule.edit');
    }

    public function delete(User $user): bool
    {
        // Only assistants and lecturers can delete schedule
        return $user->can('schedule.delete');
    }

    public function approve(User $user): bool
    {
        // Only lab tech can approve schedule
        return $user->can('schedule.approve');
    }

    public function reject(User $user): bool
    {
        // Only lab tech can reject schedule
        return $user->can('schedule.approve');
    }
}
