<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TimetableSlot;
use Illuminate\View\View;

class TeacherScheduleController extends Controller
{
    public function index(): View
    {
        $employee = auth()->user()->employee;

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $slots = collect();

        if ($employee) {
            $slots = TimetableSlot::with(['subject', 'section.schoolClass'])
                ->where('employee_id', $employee->id)
                ->orderBy('start_time')
                ->get()
                ->groupBy('day');
        }

        return view('tenant.teacher-schedule.index', compact('employee', 'slots', 'days'));
    }
}