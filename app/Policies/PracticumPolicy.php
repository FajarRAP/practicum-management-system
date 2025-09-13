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
        return $user->hasAnyRole(['assistant', 'lecturer', 'lab_tech']);
    }

    public function create(User $user)
    {
        // Only assistants and lecturers can create practicum
        return $user->hasAnyRole(['assistant', 'lecturer']);
    }

    public function update(User $user): bool
    {
        // Only assistants and lecturers can update practicum
        return $user->hasAnyRole(['assistant', 'lecturer']);
    }

    public function delete(User $user): bool
    {
        // Only assistants and lecturers can delete practicum
        return $user->hasAnyRole(['assistant', 'lecturer']);
    }

    public function view(User $user, Practicum $practicum)
    {
        // Assistants, and lecturers can view any practicum
        if ($user->hasAnyRole(['assistant', 'lecturer', 'lab_tech'])) {
            return true;
        }

        // Only Enrolled students can view the practicum
        if ($user->hasRole('student')) {
            return $user->enrollments()
                ->where('practicum_id', $practicum->id)
                // ->where('status', 'APPROVED')
                ->exists();
        }

        return false;
    }
}
