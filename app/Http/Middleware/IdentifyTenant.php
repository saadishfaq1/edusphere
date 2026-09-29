<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = $request->route('school');

        $school = School::where('subdomain', $subdomain)->first();

        if (! $school) {
            abort(404, 'School not found.');
        }

        if ($school->status === 'suspended') {
            abort(403, 'This school account is suspended.');
        }

        if ($school->status === 'trial' && $school->trial_ends_at && $school->trial_ends_at->isPast()) {
            abort(403, 'Your free trial has ended. Please contact support to upgrade your plan and continue using EDUSphere.');
        }

        app()->instance('tenant', $school);

        // Fill in the {school} route parameter automatically for every route() call
        URL::defaults(['school' => $subdomain]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($school->id);

        return $next($request);
    }
}