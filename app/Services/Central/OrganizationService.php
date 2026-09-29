<?php

namespace App\Services\Central;

use App\Models\Organization;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

class OrganizationService
{
    public function all(): Collection
    {
        return Organization::withCount('schools')->latest()->get();
    }

    public function create(array $data): Organization
    {
        return Organization::create($data);
    }

    public function summary(Organization $organization): array
    {
        $schools = $organization->schools()->with('plan')->get();

        $totalStudents = 0;
        foreach ($schools as $school) {
            $totalStudents += Student::withoutGlobalScopes()->where('school_id', $school->id)->count();
        }

        return [
            'branch_count' => $schools->count(),
            'total_students' => $totalStudents,
            'schools' => $schools,
        ];
    }

    public function delete(Organization $organization): void
    {
        if ($organization->schools()->exists()) {
            throw new \Exception('Cannot delete an organization that still has branches. Remove or reassign its schools first.');
        }

        $organization->delete();
    }
}