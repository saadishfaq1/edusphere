<?php

namespace App\Policies;

use App\Models\SchoolClass;
use App\Models\User;

class SchoolClassPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher']);
    }

    public function view(User $user, SchoolClass $schoolClass): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('School Admin');
    }

    public function update(User $user, SchoolClass $schoolClass): bool
    {
        return $user->hasRole('School Admin');
    }

    public function delete(User $user, SchoolClass $schoolClass): bool
    {
        return $user->hasRole('School Admin');
    }
}