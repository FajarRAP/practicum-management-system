<?php

namespace App\Policies;

use App\Models\AcademicYear;
use App\Models\User;

class AcademicYearPolicy
{
    public function viewAny(User $user)
    {
        return $user->can('academic_year.view');
    }

    public function delete(User $user, AcademicYear $academicYear): bool
    {
        return $user->can('academic_year.delete');
    }
}
