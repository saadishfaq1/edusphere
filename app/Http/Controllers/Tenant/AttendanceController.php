<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Services\Tenant\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendance)
    {
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', Attendance::class);

        $classes = SchoolClass::orderBy('name')->get();
        $sections = collect();
        $roster = collect();

        $schoolClassId = $request->query('school_class_id');
        $sectionId = $request->query('section_id');
        $date = $request->query('date', now()->format('Y-m-d'));

        if ($schoolClassId) {
            $sections = Section::where('school_class_id', $schoolClassId)->get();
        }

        if ($sectionId) {
            $roster = $this->attendance->rosterWithStatus((int) $sectionId, $date);
        }

        return view('tenant.attendance.create', compact('classes', 'sections', 'roster', 'schoolClassId', 'sectionId', 'date'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Attendance::class);

        $validated = $request->validate([
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'date' => ['required', 'date'],
            'status' => ['required', 'array'],
        ]);

        $this->attendance->saveBulk(
            (int) $validated['school_class_id'],
            (int) $validated['section_id'],
            $validated['date'],
            $validated['status'],
            auth()->id()
        );

        return redirect()->route('attendance.create', [
            'school_class_id' => $validated['school_class_id'],
            'section_id' => $validated['section_id'],
            'date' => $validated['date'],
        ])->with('success', 'Attendance saved successfully.');
    }

    public function report(Request $request): View
    {
        Gate::authorize('viewAny', Attendance::class);

        $sections = Section::with('schoolClass')->get();

        $sectionId = $request->query('section_id');
        $from = $request->query('from');
        $to = $request->query('to');

        $records = $this->attendance->report($sectionId ? (int) $sectionId : null, $from, $to);

        return view('tenant.attendance.report', compact('sections', 'records', 'sectionId', 'from', 'to'));
    }

    public function exportReport(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        Gate::authorize('viewAny', \App\Models\Attendance::class);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AttendanceExport(
                $request->query('section_id') ? (int) $request->query('section_id') : null,
                $request->query('from'),
                $request->query('to')
            ),
            'attendance-'.now()->format('Y-m-d').'.csv'
        );
    }
}