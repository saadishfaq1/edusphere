<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\Tenant\ExaminationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ExaminationController extends Controller
{
    public function __construct(protected ExaminationService $service)
    {
    }

    public function subjects(): View
    {
        Gate::authorize('manage-examinations');

        return view('tenant.examinations.subjects', [
            'subjects' => $this->service->subjects(),
        ]);
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        Gate::authorize('manage-examinations');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:20'],
        ]);

        $this->service->createSubject($validated);

        return back()->with('success', 'Subject added successfully.');
    }

    public function index(): View
    {
        Gate::authorize('manage-examinations');

        return view('tenant.examinations.index', [
            'exams' => $this->service->exams(),
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-examinations');

        $validated = $request->validate([
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'name' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $this->service->createExam($validated);

        return redirect()->route('examinations.index')->with('success', 'Exam created successfully.');
    }

    public function show(Request $request): View
    {
        Gate::authorize('manage-examinations');

        $exam = Exam::with('examSubjects.subject')->findOrFail($request->route('exam'));
        $subjects = $this->service->subjects();

        $students = Student::where('school_class_id', $exam->school_class_id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        return view('tenant.examinations.show', compact('exam', 'subjects', 'students'));
    }

    public function attachSubject(Request $request): RedirectResponse
    {
        Gate::authorize('manage-examinations');

        $exam = Exam::findOrFail($request->route('exam'));

        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'max_marks' => ['required', 'integer', 'min:1'],
            'passing_marks' => ['required', 'integer', 'min:0', 'lte:max_marks'],
        ]);

        $this->service->attachSubject($exam, (int) $validated['subject_id'], (int) $validated['max_marks'], (int) $validated['passing_marks']);

        return back()->with('success', 'Subject added to exam.');
    }

    public function markEntry(Request $request): View
    {
        Gate::authorize('manage-examinations');

        $examSubject = ExamSubject::with(['exam', 'subject'])->findOrFail($request->route('examSubject'));
        $roster = $this->service->rosterWithMarks($examSubject);

        return view('tenant.examinations.marks', compact('examSubject', 'roster'));
    }

    public function saveMarks(Request $request): RedirectResponse
    {
        Gate::authorize('manage-examinations');

        $examSubject = ExamSubject::findOrFail($request->route('examSubject'));

        $validated = $request->validate(['marks' => ['required', 'array']]);

        $this->service->saveMarks($examSubject, $validated['marks']);

        return redirect()->route('exams.marks', $examSubject->id)->with('success', 'Marks saved successfully.');
    }

    public function reportCard(Request $request): View
    {
        Gate::authorize('manage-examinations');

        $exam = Exam::findOrFail($request->route('exam'));
        $student = Student::findOrFail($request->route('student'));

        $results = $this->service->reportCard($exam, $student);

        return view('tenant.examinations.report-card', compact('exam', 'student', 'results'));
    }
}