<?php

namespace App\Policies;

use App\Models\User;

class ExaminationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher']);
    }

    public function manage(User $user): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher']);
    }
}