<?php

namespace App\Services\Tenant;

use App\Models\PromotionLog;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

class PromotionService
{
    public function roster(int $schoolClassId, ?int $sectionId): Collection
    {
        return Student::where('school_class_id', $schoolClassId)
            ->where('status', 'active')
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
            ->orderBy('first_name')
            ->get();
    }

    public function promote(array $studentIds, int $toClassId, ?int $toSectionId, int $promotedBy): int
    {
        $students = Student::whereIn('id', $studentIds)->get();

        foreach ($students as $student) {
            PromotionLog::create([
                'student_id' => $student->id,
                'from_school_class_id' => $student->school_class_id,
                'from_section_id' => $student->section_id,
                'to_school_class_id' => $toClassId,
                'to_section_id' => $toSectionId,
                'action' => 'promoted',
                'promoted_by' => $promotedBy,
            ]);

            $student->update([
                'school_class_id' => $toClassId,
                'section_id' => $toSectionId,
                'roll_number' => null,
            ]);
        }

        return $students->count();
    }

    public function graduate(array $studentIds, int $promotedBy): int
    {
        $students = Student::whereIn('id', $studentIds)->get();

        foreach ($students as $student) {
            PromotionLog::create([
                'student_id' => $student->id,
                'from_school_class_id' => $student->school_class_id,
                'from_section_id' => $student->section_id,
                'to_school_class_id' => null,
                'to_section_id' => null,
                'action' => 'graduated',
                'promoted_by' => $promotedBy,
            ]);

            $student->update([
                'status' => 'graduated',
                'school_class_id' => null,
                'section_id' => null,
                'roll_number' => null,
            ]);
        }

        return $students->count();
    }

    public function history()
    {
        return PromotionLog::with(['student', 'toSchoolClass' => fn ($q) => $q, 'fromSchoolClass' => fn ($q) => $q])
            ->latest()
            ->take(50)
            ->get();
    }
}