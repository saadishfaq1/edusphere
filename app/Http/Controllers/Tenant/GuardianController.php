<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Student;
use App\Services\Tenant\GuardianService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GuardianController extends Controller
{
    public function __construct(protected GuardianService $guardians)
    {
    }

    public function index(Request $request): View
    {
        Gate::authorize('manage-guardians');

        $schoolClassId = $request->query('school_class_id');
        $sectionId = $request->query('section_id');
        $search = $request->query('search');

        $students = Student::where('status', 'active')
            ->when($schoolClassId, fn ($q) => $q->where('school_class_id', $schoolClassId))
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
            ->when($search, function ($q, $search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('admission_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('first_name')
            ->get();

        return view('tenant.guardians.index', [
            'guardians' => $this->guardians->all(),
            'students' => $students,
            'classes' => \App\Models\SchoolClass::orderBy('name')->get(),
            'sections' => $sectionId ? \App\Models\Section::where('school_class_id', $schoolClassId)->get() : collect(),
            'schoolClassId' => $schoolClassId,
            'sectionId' => $sectionId,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-guardians');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:guardians,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:20'],
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        $this->guardians->create($validated);

        return back()->with('success', 'Parent portal account created.');
    }

    public function updateStudents(Request $request): RedirectResponse
    {
        Gate::authorize('manage-guardians');

        $guardian = Guardian::findOrFail($request->route('guardian'));

        $validated = $request->validate([
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        $this->guardians->updateStudents($guardian, $validated['student_ids'] ?? []);

        return back()->with('success', 'Linked children updated.');
    }
}