<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Academics\StoreClassRequest;
use App\Http\Requests\Tenant\Academics\UpdateClassRequest;
use App\Models\SchoolClass;
use App\Services\Tenant\ClassService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function __construct(protected ClassService $classes)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', SchoolClass::class);

        return view('tenant.academics.classes.index', [
            'classes' => $this->classes->all(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', SchoolClass::class);

        return view('tenant.academics.classes.create');
    }

    public function store(StoreClassRequest $request): RedirectResponse
    {
        $this->classes->create($request->validated());

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(): View
    {
        $school_class = SchoolClass::findOrFail(request()->route('school_class'));
        $this->authorize('update', $school_class);
        return view('tenant.academics.classes.edit', ['class' => $school_class]);
    }

    public function update(UpdateClassRequest $request): RedirectResponse
    {
        $school_class = SchoolClass::findOrFail($request->route('school_class'));
        $this->classes->update($school_class, $request->validated());
        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(): RedirectResponse
    {
        $school_class = SchoolClass::findOrFail(request()->route('school_class'));
        $this->authorize('delete', $school_class);
        $this->classes->delete($school_class);
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}