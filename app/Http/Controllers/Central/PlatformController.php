<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\School;
use App\Services\Central\PlatformService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformController extends Controller
{
    public function __construct(protected PlatformService $platform)
    {
    }

    public function dashboard(): View
    {
        return view('central.dashboard', [
            'summary' => $this->platform->platformSummary(),
            'growthTrend' => $this->platform->growthTrend(),
            'statusBreakdown' => $this->platform->statusBreakdown(),
            'planDistribution' => $this->platform->planDistribution(),
        ]);
    }

    public function schools(): View
    {
        return view('central.schools.index', ['schools' => $this->platform->schools()]);
    }

    public function createSchool(): View
    {
        return view('central.schools.create', [
            'plans' => $this->platform->plans(),
            'organizations' => \App\Models\Organization::orderBy('name')->get(),
        ]);
    }

    public function storeSchool(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'subdomain' => ['required', 'string', 'max:63', 'alpha_dash', 'unique:schools,subdomain'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'admin_name' => ['required', 'string', 'max:150'],
            'admin_email' => ['required', 'email', 'max:255'],
            'admin_password' => ['required', 'string', 'min:6'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'branch_name' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['status'] = 'trial';
        $validated['trial_ends_at'] = now()->addDays(14);

        $this->platform->createSchool($validated);

        return redirect()->route('central.schools')->with('success', 'School onboarded successfully. Admin account created.');
    }

    public function showSchool(Request $request): View
    {
        $school = School::with('plan')->findOrFail($request->route('school'));

        return view('central.schools.show', [
            'school' => $school,
            'plans' => $this->platform->plans(),
            'studentCount' => $this->platform->studentCountFor($school),
        ]);
    }

    public function updateStatus(Request $request): RedirectResponse
    {
        $school = School::findOrFail($request->route('school'));

        $validated = $request->validate(['status' => ['required', 'in:trial,active,suspended']]);

        $this->platform->updateSchoolStatus($school, $validated['status']);

        return back()->with('success', 'School status updated.');
    }

    public function assignPlan(Request $request): RedirectResponse
    {
        $school = School::findOrFail($request->route('school'));

        $validated = $request->validate(['plan_id' => ['nullable', 'exists:plans,id']]);

        $this->platform->assignPlan($school, $validated['plan_id'] ?? null);

        return back()->with('success', 'Plan assigned successfully.');
    }

    public function plans(): View
    {
        return view('central.plans.index', ['plans' => $this->platform->plans()]);
    }

    public function storePlan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'max_students' => ['nullable', 'integer', 'min:1'],
            'max_staff' => ['nullable', 'integer', 'min:1'],
            'modules' => ['nullable', 'array'],
        ]);

        $validated['modules'] = empty($validated['modules']) ? null : $validated['modules'];

        $this->platform->createPlan($validated);

        return back()->with('success', 'Plan created successfully.');
    }

    public function settings(): View
    {
        return view('central.settings', ['settings' => \App\Models\PlatformSetting::current()]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'logo' => ['nullable', 'image', 'max:2048'],
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $settings = \App\Models\PlatformSetting::current();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($settings->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('platform', 'public');
        }

        unset($validated['logo']);

        $settings->update($validated);

        return back()->with('success', 'Platform settings updated.');
    }

    public function updateModules(Request $request): RedirectResponse
    {
        $school = School::findOrFail($request->route('school'));

        $validated = $request->validate(['custom' => ['nullable'], 'modules' => ['nullable', 'array']]);

        $school->update([
            'modules' => $request->boolean('custom') ? ($validated['modules'] ?? []) : null,
        ]);

        return back()->with('success', 'Module access updated.');
    }

    public function destroySchool(Request $request): RedirectResponse
    {
        $school = School::findOrFail($request->route('school'));

        $this->platform->deleteSchool($school);

        return redirect()->route('central.schools')->with('success', 'School deleted successfully.');
    }

    public function extendTrial(Request $request): RedirectResponse
    {
        $school = School::findOrFail($request->route('school'));

        $validated = $request->validate(['days' => ['required', 'integer', 'min:1', 'max:365']]);

        $this->platform->extendTrial($school, (int) $validated['days']);

        return back()->with('success', 'Trial extended successfully.');
    }
}