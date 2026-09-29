<?php

namespace App\Services\Tenant;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AttendanceService
{
    public function rosterWithStatus(int $sectionId, string $date): Collection
    {
        $students = Student::where('section_id', $sectionId)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        $existing = Attendance::where('section_id', $sectionId)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

        return $students->map(function (Student $student) use ($existing) {
            $student->current_status = $existing->get($student->id)?->status;

            return $student;
        });
    }

    public function saveBulk(int $schoolClassId, int $sectionId, string $date, array $statuses, int $markedBy): void
    {
        foreach ($statuses as $studentId => $status) {
            if (! in_array($status, ['present', 'absent', 'late', 'leave'])) {
                continue;
            }

            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $date],
                [
                    'school_class_id' => $schoolClassId,
                    'section_id' => $sectionId,
                    'status' => $status,
                    'marked_by' => $markedBy,
                ]
            );
        }
    }

    public function report(?int $sectionId, ?string $from, ?string $to): Collection
    {
        return Attendance::with(['student', 'section.schoolClass'])
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
            ->when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->orderByDesc('date')
            ->get();
    }
}