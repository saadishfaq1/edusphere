<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (! app('tenant')->hasModule($module)) {
            abort(403, 'This module is not included in your current plan. Contact support to upgrade.');
        }

        return $next($request);
    }
}