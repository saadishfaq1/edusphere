<?php

namespace App\Services\Tenant;

use App\Models\Guardian;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class GuardianService
{
    public function all(): Collection
    {
        return Guardian::with('students')->orderBy('name')->get();
    }

    public function create(array $data): Guardian
    {
        $guardian = Guardian::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'email_verified_at' => now(),
        ]);

        $guardian->students()->sync($data['student_ids'] ?? []);

        return $guardian;
    }

    public function updateStudents(Guardian $guardian, array $studentIds): Guardian
    {
        $guardian->students()->sync($studentIds);

        return $guardian->fresh();
    }
}