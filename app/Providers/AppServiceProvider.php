<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Student;
use App\Policies\StudentPolicy;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Policies\SchoolClassPolicy;
use App\Policies\SectionPolicy;
use App\Models\Attendance;
use App\Policies\AttendancePolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(SchoolClass::class, SchoolClassPolicy::class);
        Gate::policy(Section::class, SectionPolicy::class);
        Gate::policy(Attendance::class, AttendancePolicy::class);
        Gate::define('manage-examinations', function ($user) {
            return $user->hasAnyRole(['School Admin', 'Teacher']);
        });
        Gate::define('manage-fees', function ($user) {
            return $user->hasRole('School Admin');
        });
        Gate::define('manage-hr', function ($user) {
            return $user->hasRole('School Admin');
        });
        Gate::define('manage-library', function ($user) {
            return $user->hasAnyRole(['School Admin', 'Librarian']);
        });
        Gate::define('manage-transport', function ($user) {
            return $user->hasRole('School Admin');
        });
        Gate::define('manage-finance', function ($user) {
            return $user->hasRole('School Admin');
        });
        Gate::define('manage-settings', function ($user) {
            return $user->hasRole('School Admin');
        });
        Gate::define('manage-timetable', function ($user) {
            return $user->hasAnyRole(['School Admin', 'Teacher']);
        });
        Gate::define('manage-guardians', function ($user) {
            return $user->hasRole('School Admin');
        });
        Gate::define('manage-promotion', function ($user) {
            return $user->hasRole('School Admin');
        });
        Gate::define('view-activity-log', function ($user) {
            return $user->hasRole('School Admin');
        });
    }
}
