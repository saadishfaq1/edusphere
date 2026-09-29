<?php

namespace App\Services\Tenant;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return Student::with(['schoolClass', 'section'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('admission_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Student
    {
        $tenant = app('tenant');

        if ($tenant->plan && $tenant->plan->max_students) {
            $currentCount = Student::where('status', 'active')->count();

            if ($currentCount >= $tenant->plan->max_students) {
                throw new \Exception("You've reached your plan's limit of {$tenant->plan->max_students} students. Contact support to upgrade your plan.");
            }
        }

        $data['admission_number'] = $this->generateAdmissionNumber();

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo_path'] = $data['photo']->store('students', 'public');
        }

        unset($data['photo']);

        return Student::create($data);
    }

    public function update(Student $student, array $data): Student
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $data['photo_path'] = $data['photo']->store('students', 'public');
        }

        unset($data['photo']);

        $student->update($data);

        return $student->fresh();
    }

    public function delete(Student $student): void
    {
        \App\Services\Tenant\ActivityLogger::log(
            'student_deleted',
            "Deleted student {$student->full_name} ({$student->admission_number})",
            $student
        );

        $student->delete();
    }

    protected function generateAdmissionNumber(): string
    {
        $year = now()->format('Y');

        $lastNumber = Student::withTrashed()
            ->where('admission_number', 'like', "{$year}-%")
            ->orderByDesc('admission_number')
            ->value('admission_number');

        $sequence = $lastNumber
            ? ((int) substr($lastNumber, 5)) + 1
            : 1;

        return $year.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}