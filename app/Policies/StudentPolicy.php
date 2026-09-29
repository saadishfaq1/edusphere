<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher', 'Accountant']);
    }

    public function view(User $user, Student $student): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher', 'Accountant']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('School Admin');
    }

    public function update(User $user, Student $student): bool
    {
        return $user->hasRole('School Admin');
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->hasRole('School Admin');
    }
}