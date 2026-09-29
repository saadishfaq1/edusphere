<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Services\Tenant\PromotionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function __construct(protected PromotionService $promotion)
    {
    }

    public function index(Request $request): View
    {
        Gate::authorize('manage-promotion');

        $classes = SchoolClass::orderBy('sort_order')->orderBy('name')->get();

        $fromClassId = $request->query('from_class_id');
        $fromSectionId = $request->query('from_section_id');

        $fromSections = $fromClassId ? Section::where('school_class_id', $fromClassId)->get() : collect();
        $roster = $fromClassId ? $this->promotion->roster((int) $fromClassId, $fromSectionId ? (int) $fromSectionId : null) : collect();

        return view('tenant.promotion.index', [
            'classes' => $classes,
            'fromClassId' => $fromClassId,
            'fromSectionId' => $fromSectionId,
            'fromSections' => $fromSections,
            'roster' => $roster,
            'history' => $this->promotion->history(),
        ]);
    }

    public function sectionsFor(Request $request)
    {
        Gate::authorize('manage-promotion');

        $sections = Section::where('school_class_id', $request->query('class_id'))->get(['id', 'name']);

        return response()->json($sections);
    }

    public function promote(Request $request): RedirectResponse
    {
        Gate::authorize('manage-promotion');

        $validated = $request->validate([
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['exists:students,id'],
            'action' => ['required', 'in:promote,graduate'],
            'to_class_id' => ['required_if:action,promote', 'nullable', 'exists:school_classes,id'],
            'to_section_id' => ['nullable', 'exists:sections,id'],
        ]);

        if ($validated['action'] === 'graduate') {
            $count = $this->promotion->graduate($validated['student_ids'], auth()->id());
            $message = "{$count} student(s) marked as graduated.";
        } else {
            $count = $this->promotion->promote(
                $validated['student_ids'],
                (int) $validated['to_class_id'],
                $validated['to_section_id'] ? (int) $validated['to_section_id'] : null,
                auth()->id()
            );
            $message = "{$count} student(s) promoted successfully.";
        }

        return redirect()->route('promotion.index')->with('success', $message);
    }
}