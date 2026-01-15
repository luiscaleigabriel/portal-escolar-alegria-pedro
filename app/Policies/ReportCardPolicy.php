<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class ReportCardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function view(User $user, Student $student)
    {
        return
            $user->hasRole('director') ||
            $user->hasRole('teacher') ||
            $user->id === $student->user_id ||
            $student->guardians()->where('user_id', $user->id)->exists();
    }
}
