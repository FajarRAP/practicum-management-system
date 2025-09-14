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
        // Only assistants and lecturers can view any practicum
        return $user->can('practicum.view');
    }

    public function create(User $user)
    {
        // Only assistants and lecturers can create practicum
        return $user->can('practicum.create');
    }

    public function delete(User $user): bool
    {
        // Only assistants and lecturers can delete practicum
        return $user->can('practicum.delete');
    }

    public function view(User $user, Practicum $practicum)
    {
        // Assistants, and lecturers can view any practicum
        if ($user->can('practicum.view')) {
            return true;
        }

        // Only Enrolled students can view the practicum
        if ($user->can('practicum.enter')) {
            return true;
            // return $user->enrollments()
            //     ->where('practicum_id', $practicum->id)
            //     // ->where('status', 'APPROVED')
            //     ->exists();
        }

        return false;
    }
}
