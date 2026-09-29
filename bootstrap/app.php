<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', SubstituteBindings::class, \App\Http\Middleware\SetLocale::class])
                ->domain(config('app.central_domain'))
                ->group(base_path('routes/central.php'));

            Route::middleware(['web', SubstituteBindings::class, \App\Http\Middleware\SetLocale::class, \App\Http\Middleware\IdentifyTenant::class])
                ->domain('{school}.'.config('app.tenant_domain'))
                ->group(base_path('routes/tenant.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['module' => \App\Http\Middleware\EnsureModuleEnabled::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();