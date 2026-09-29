<?php

namespace App\Policies;

use App\Models\Section;
use App\Models\User;

class SectionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher']);
    }

    public function view(User $user, Section $section): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('School Admin');
    }

    public function update(User $user, Section $section): bool
    {
        return $user->hasRole('School Admin');
    }

    public function delete(User $user, Section $section): bool
    {
        return $user->hasRole('School Admin');
    }
}