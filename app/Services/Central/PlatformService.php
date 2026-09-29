<?php

namespace App\Services\Central;

use App\Models\Plan;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PlatformService
{
    public function schools(): Collection
    {
        return School::with('plan')->withCount('users')->latest()->get();
    }

    public function createSchool(array $data): School
    {
        $adminName = $data['admin_name'];
        $adminEmail = $data['admin_email'];
        $adminPassword = $data['admin_password'];

        unset($data['admin_name'], $data['admin_email'], $data['admin_password']);

        $school = School::create($data);

        $user = \App\Models\User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => \Illuminate\Support\Facades\Hash::make($adminPassword),
            'school_id' => $school->id,
            'email_verified_at' => now(),
        ]);

        // Set up default roles for this school and assign Admin
        \Spatie\Permission\PermissionRegistrar::class;
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($school->id);

        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'School Admin', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Teacher', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Parent', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Accountant', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Librarian', 'guard_name' => 'web']);

        $user->assignRole($adminRole);

        return $school;
    }

    public function updateSchoolStatus(School $school, string $status): School
    {
        $school->update(['status' => $status]);

        return $school->fresh();
    }

    public function assignPlan(School $school, ?int $planId): School
    {
        $school->update(['plan_id' => $planId]);

        return $school->fresh();
    }

    public function plans(): Collection
    {
        return Plan::latest()->get();
    }

    public function createPlan(array $data): Plan
    {
        return Plan::create($data);
    }

    public function updatePlan(Plan $plan, array $data): Plan
    {
        $plan->update($data);

        return $plan->fresh();
    }

    public function studentCountFor(School $school): int
    {
        return Student::withoutGlobalScopes()->where('school_id', $school->id)->count();
    }

    public function isOverLimit(School $school): bool
    {
        if (! $school->plan || ! $school->plan->max_students) {
            return false;
        }

        return $this->studentCountFor($school) >= $school->plan->max_students;
    }

    public function platformSummary(): array
    {
        return [
            'total_schools' => School::count(),
            'active_schools' => School::where('status', 'active')->count(),
            'trial_schools' => School::where('status', 'trial')->count(),
            'suspended_schools' => School::where('status', 'suspended')->count(),
            'total_students' => Student::withoutGlobalScopes()->count(),
        ];
    }

    public function growthTrend(int $months = 6): array
    {
        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = School::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function statusBreakdown(): array
    {
        $counts = School::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        return [
            'trial' => (int) ($counts['trial'] ?? 0),
            'active' => (int) ($counts['active'] ?? 0),
            'suspended' => (int) ($counts['suspended'] ?? 0),
        ];
    }

    public function planDistribution(): array
    {
        $rows = Plan::withCount('schools')->get();

        return [
            'labels' => $rows->pluck('name')->toArray(),
            'data' => $rows->pluck('schools_count')->toArray(),
        ];
    }

    public function deleteSchool(School $school): void
    {
        $school->delete();
    }

    public function extendTrial(School $school, int $days): School
    {
        $school->update([
            'trial_ends_at' => now()->addDays($days),
        ]);

        return $school->fresh();
    }
}