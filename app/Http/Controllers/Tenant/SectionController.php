<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Academics\StoreSectionRequest;
use App\Http\Requests\Tenant\Academics\UpdateSectionRequest;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Services\Tenant\SectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function __construct(protected SectionService $sections)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Section::class);

        return view('tenant.academics.sections.index', [
            'sections' => $this->sections->all(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Section::class);

        return view('tenant.academics.sections.create', [
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }

    public function store(StoreSectionRequest $request): RedirectResponse
    {
        $this->sections->create($request->validated());

        return redirect()->route('sections.index')->with('success', 'Section created successfully.');
    }

    public function edit(): View
    {
        $section = Section::findOrFail(request()->route('section'));
        $this->authorize('update', $section);
        return view('tenant.academics.sections.edit', [
            'section' => $section,
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateSectionRequest $request): RedirectResponse
    {
        $section = Section::findOrFail($request->route('section'));
        $this->sections->update($section, $request->validated());
        return redirect()->route('sections.index')->with('success', 'Section updated successfully.');
    }

    public function destroy(): RedirectResponse
    {
        $section = Section::findOrFail(request()->route('section'));
        $this->authorize('delete', $section);
        $this->sections->delete($section);
        return redirect()->route('sections.index')->with('success', 'Section deleted successfully.');
    }
}