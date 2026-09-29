<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Students\StoreStudentRequest;
use App\Http\Requests\Tenant\Students\UpdateStudentRequest;
use App\Models\Student;
use App\Services\Tenant\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(protected StudentService $students)
    {
    }

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Student::class);

        $search = $request->query('search');

        $students = $this->students->paginate(search: $search)->withQueryString();

        return view('tenant.students.index', compact('students', 'search'));
    }

    public function create(): View
    {
        Gate::authorize('create', Student::class);

        return view('tenant.students.create');
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        Gate::authorize('create', Student::class);

        $data = $request->validated();
        $data['photo'] = $request->file('photo');

        try {
            $this->students->create($data);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit(): View
    {
        $student = Student::findOrFail(request()->route('student'));
        $this->authorize('update', $student);
        return view('tenant.students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request): RedirectResponse
    {
        $student = Student::findOrFail($request->route('student'));

        $data = $request->validated();
        $data['photo'] = $request->file('photo');

        $this->students->update($student, $data);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(): RedirectResponse
    {
        $student = Student::findOrFail(request()->route('student'));
        $this->authorize('delete', $student);
        $this->students->delete($student);
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        Gate::authorize('viewAny', Student::class);

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentsExport, 'students-'.now()->format('Y-m-d').'.csv');
    }

    public function importTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        Gate::authorize('create', Student::class);

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentImportTemplateExport, 'student-import-template.csv');
    }

    public function showImport(): View
    {
        Gate::authorize('create', Student::class);

        return view('tenant.students.import');
    }

    public function processImport(Request $request): View
    {
        Gate::authorize('create', Student::class);

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xlsx,xls', 'max:5120'],
        ]);

        $import = new \App\Imports\StudentsImport;

        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

        return view('tenant.students.import-result', [
            'successCount' => $import->successCount,
            'errors' => $import->errors,
        ]);
    }
}