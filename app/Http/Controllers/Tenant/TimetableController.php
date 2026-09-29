<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TimetableSlot;
use App\Services\Tenant\TimetableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function __construct(protected TimetableService $timetable)
    {
    }

    public function index(Request $request): View
    {
        Gate::authorize('manage-timetable');

        $sections = Section::with('schoolClass')->get();
        $sectionId = $request->query('section_id');
        $slots = collect();

        if ($sectionId) {
            $slots = $this->timetable->forSection((int) $sectionId);
        }

        return view('tenant.timetable.index', [
            'sections' => $sections,
            'sectionId' => $sectionId,
            'slots' => $slots,
            'days' => $this->timetable->days(),
            'subjects' => Subject::orderBy('name')->get(),
            'employees' => Employee::where('status', 'active')->orderBy('first_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-timetable');

        $validated = $request->validate([
            'section_id' => ['required', 'exists:sections,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'day' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
        ]);

        try {
            $this->timetable->createSlot($validated);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('timetable.index', ['section_id' => $validated['section_id']])->with('success', 'Class added to timetable.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Gate::authorize('manage-timetable');

        $slot = TimetableSlot::findOrFail($request->route('slot'));
        $sectionId = $slot->section_id;

        $this->timetable->deleteSlot($slot);

        return redirect()->route('timetable.index', ['section_id' => $sectionId])->with('success', 'Class removed from timetable.');
    }
}