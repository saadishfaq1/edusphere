<?php

namespace App\Services\Tenant;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

class ExaminationService
{
    public function subjects()
    {
        return \App\Models\Subject::orderBy('name')->get();
    }

    public function createSubject(array $data)
    {
        return \App\Models\Subject::create($data);
    }

    public function exams(): Collection
    {
        return Exam::with('schoolClass')->latest()->get();
    }

    public function createExam(array $data): Exam
    {
        return Exam::create($data);
    }

    public function attachSubject(Exam $exam, int $subjectId, int $maxMarks, int $passingMarks): ExamSubject
    {
        return ExamSubject::firstOrCreate(
            ['exam_id' => $exam->id, 'subject_id' => $subjectId],
            ['max_marks' => $maxMarks, 'passing_marks' => $passingMarks]
        );
    }

    public function rosterWithMarks(ExamSubject $examSubject): Collection
    {
        $students = Student::where('school_class_id', $examSubject->exam->school_class_id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        $existing = ExamResult::where('exam_subject_id', $examSubject->id)
            ->get()
            ->keyBy('student_id');

        return $students->map(function (Student $student) use ($existing) {
            $student->obtained_marks = $existing->get($student->id)?->obtained_marks;

            return $student;
        });
    }

    public function saveMarks(ExamSubject $examSubject, array $marks): void
    {
        foreach ($marks as $studentId => $obtained) {
            if ($obtained === '' || $obtained === null) {
                continue;
            }

            ExamResult::updateOrCreate(
                ['exam_subject_id' => $examSubject->id, 'student_id' => $studentId],
                ['obtained_marks' => min((int) $obtained, $examSubject->max_marks)]
            );
        }

        \App\Services\Tenant\ActivityLogger::log(
            'marks_entered',
            "Entered marks for {$examSubject->subject->name} — {$examSubject->exam->name}",
            $examSubject,
            ['student_count' => count($marks)]
        );
    }

    public function reportCard(Exam $exam, Student $student): Collection
    {
        return ExamSubject::with('subject')
            ->where('exam_id', $exam->id)
            ->get()
            ->map(function (ExamSubject $examSubject) use ($student) {
                $result = ExamResult::where('exam_subject_id', $examSubject->id)
                    ->where('student_id', $student->id)
                    ->first();

                $examSubject->obtained_marks = $result?->obtained_marks;

                return $examSubject;
            });
    }
}