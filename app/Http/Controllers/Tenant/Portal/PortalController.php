<?php

namespace App\Http\Controllers\Tenant\Portal;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\TimetableSlot;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function dashboard(): View
    {
        $children = auth('guardian')->user()->students()->with('schoolClass', 'section')->get();

        return view('portal.dashboard', compact('children'));
    }

    protected function authorizedChild(Request $request): Student
    {
        $student = Student::findOrFail($request->route('student'));

        abort_unless(
            auth('guardian')->user()->students()->where('students.id', $student->id)->exists(),
            403
        );

        return $student;
    }

    public function attendance(Request $request): View
    {
        $student = $this->authorizedChild($request);

        $records = Attendance::where('student_id', $student->id)->orderByDesc('date')->take(60)->get();

        return view('portal.attendance', compact('student', 'records'));
    }

    public function fees(Request $request): View
    {
        $student = $this->authorizedChild($request);

        $invoices = Invoice::where('student_id', $student->id)->latest()->get();

        return view('portal.fees', compact('student', 'invoices'));
    }

    public function results(Request $request): View
    {
        $student = $this->authorizedChild($request);

        $exams = \App\Models\Exam::where('school_class_id', $student->school_class_id)->orderByDesc('start_date')->get();

        return view('portal.results', compact('student', 'exams'));
    }

    public function timetable(Request $request): View
    {
        $student = $this->authorizedChild($request);

        $slots = collect();
        if ($student->section_id) {
            $slots = TimetableSlot::with('subject')
                ->where('section_id', $student->section_id)
                ->orderBy('start_time')
                ->get()
                ->groupBy('day');
        }

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        return view('portal.timetable', compact('student', 'slots', 'days'));
    }

    public function reportCard(Request $request, \App\Services\Tenant\ExaminationService $examinations): View
    {
        $student = $this->authorizedChild($request);

        $exam = \App\Models\Exam::findOrFail($request->route('exam'));

        abort_unless($exam->school_class_id === $student->school_class_id, 404);

        $results = $examinations->reportCard($exam, $student);

        return view('portal.report-card', compact('exam', 'student', 'results'));
    }
}