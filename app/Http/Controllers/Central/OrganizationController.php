<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Services\Central\OrganizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function __construct(protected OrganizationService $organizations)
    {
    }

    public function index(): View
    {
        return view('central.organizations.index', ['organizations' => $this->organizations->all()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $this->organizations->create($validated);

        return back()->with('success', 'Organization created successfully.');
    }

    public function show(Request $request): View
    {
        $organization = Organization::findOrFail($request->route('organization'));

        return view('central.organizations.show', [
            'organization' => $organization,
            'summary' => $this->organizations->summary($organization),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $organization = Organization::findOrFail($request->route('organization'));

        try {
            $this->organizations->delete($organization);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('central.organizations')->with('success', 'Organization deleted successfully.');
    }
}