<?php

use App\Http\Controllers\Central\AuthController;
use App\Http\Controllers\Central\PlatformController;
use App\Http\Controllers\Central\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('central.login');
    Route::post('/login', [AuthController::class, 'store'])->name('central.login.store');
});

Route::middleware(['auth', \App\Http\Middleware\EnsurePlatformAdmin::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('central.logout');

    Route::get('/', [PlatformController::class, 'dashboard'])->name('central.dashboard');

    Route::get('/schools', [PlatformController::class, 'schools'])->name('central.schools');
    Route::get('/schools/create', [PlatformController::class, 'createSchool'])->name('central.schools.create');
    Route::post('/schools', [PlatformController::class, 'storeSchool'])->name('central.schools.store');
    Route::get('/schools/{school}', [PlatformController::class, 'showSchool'])->name('central.schools.show');
    Route::post('/schools/{school}/status', [PlatformController::class, 'updateStatus'])->name('central.schools.status');
    Route::post('/schools/{school}/plan', [PlatformController::class, 'assignPlan'])->name('central.schools.plan');

    Route::get('/plans', [PlatformController::class, 'plans'])->name('central.plans');
    Route::post('/plans', [PlatformController::class, 'storePlan'])->name('central.plans.store');

    Route::get('/settings', [PlatformController::class, 'settings'])->name('central.settings');
    Route::post('/settings', [PlatformController::class, 'updateSettings'])->name('central.settings.update');

    Route::post('/schools/{school}/modules', [PlatformController::class, 'updateModules'])->name('central.schools.modules');

    Route::get('/organizations', [OrganizationController::class, 'index'])->name('central.organizations');
    Route::post('/organizations', [OrganizationController::class, 'store'])->name('central.organizations.store');
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('central.organizations.show');
    Route::delete('/schools/{school}', [PlatformController::class, 'destroySchool'])->name('central.schools.destroy');
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('central.organizations.destroy');

    Route::post('/schools/{school}/extend-trial', [PlatformController::class, 'extendTrial'])->name('central.schools.extend-trial');

    Route::get('/locale/{locale}', [\App\Http\Controllers\LocaleController::class, 'switch'])->name('central.locale.switch');
});