<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['School Admin', 'Teacher']);
    }
}