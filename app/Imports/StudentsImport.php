<?php

namespace App\Imports;

use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public int $successCount = 0;
    public array $errors = [];

    public function collection(Collection $rows)
    {
        $classCache = SchoolClass::pluck('id', 'name');
        $rowNumber = 1; // heading row is row 1, data starts at row 2

        foreach ($rows as $row) {
            $rowNumber++;

            $data = [
                'admission_number' => trim($row['admission_number_leave_blank_to_autogenerate'] ?? ''),
                'first_name' => trim($row['first_name'] ?? ''),
                'last_name' => trim($row['last_name'] ?? ''),
                'date_of_birth' => trim($row['date_of_birth_yyyy_mm_dd'] ?? ''),
                'gender' => strtolower(trim($row['gender_male_female_other'] ?? '')),
                'class_name' => trim($row['class_name_must_already_exist'] ?? ''),
                'section_name' => trim($row['section_name_optional_must_already_exist'] ?? ''),
                'roll_number' => trim($row['roll_number'] ?? ''),
                'email' => trim($row['email'] ?? ''),
                'phone' => trim($row['phone'] ?? ''),
                'address' => trim($row['address'] ?? ''),
                'guardian_name' => trim($row['guardian_name'] ?? ''),
                'guardian_phone' => trim($row['guardian_phone'] ?? ''),
                'guardian_email' => trim($row['guardian_email'] ?? ''),
                'guardian_relation' => trim($row['guardian_relation'] ?? ''),
                'admission_date' => trim($row['admission_date_yyyy_mm_dd'] ?? ''),
                'status' => strtolower(trim($row['status_active_inactive_graduated_transferred'] ?? 'active')),
            ];

            if (empty($data['first_name']) && empty($data['last_name'])) {
                continue; // silently skip fully blank rows
            }

            $validator = Validator::make($data, [
                'admission_number' => ['nullable', 'string', 'unique:students,admission_number'],
                'first_name' => ['required', 'string', 'max:100'],
                'last_name' => ['required', 'string', 'max:100'],
                'date_of_birth' => ['required', 'date'],
                'gender' => ['required', 'in:male,female,other'],
                'class_name' => ['required', 'string'],
                'email' => ['nullable', 'email'],
                'admission_date' => ['required', 'date'],
                'status' => ['required', 'in:active,inactive,graduated,transferred'],
            ]);

            if ($validator->fails()) {
                $this->errors[] = "Row {$rowNumber}: ".implode(' ', $validator->errors()->all());
                continue;
            }

            $classId = $classCache->get($data['class_name']);

            if (! $classId) {
                $this->errors[] = "Row {$rowNumber}: Class \"{$data['class_name']}\" does not exist. Create it first under Academics.";
                continue;
            }

            $sectionId = null;
            if (! empty($data['section_name'])) {
                $section = Section::where('school_class_id', $classId)->where('name', $data['section_name'])->first();

                if (! $section) {
                    $this->errors[] = "Row {$rowNumber}: Section \"{$data['section_name']}\" does not exist under \"{$data['class_name']}\".";
                    continue;
                }

                $sectionId = $section->id;
            }

            Student::create([
                'admission_number' => $data['admission_number'] ?: $this->generateAdmissionNumber(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'school_class_id' => $classId,
                'section_id' => $sectionId,
                'roll_number' => $data['roll_number'] ?: null,
                'email' => $data['email'] ?: null,
                'phone' => $data['phone'] ?: null,
                'address' => $data['address'] ?: null,
                'guardian_name' => $data['guardian_name'] ?: null,
                'guardian_phone' => $data['guardian_phone'] ?: null,
                'guardian_email' => $data['guardian_email'] ?: null,
                'guardian_relation' => $data['guardian_relation'] ?: null,
                'admission_date' => $data['admission_date'],
                'status' => $data['status'],
            ]);

            $this->successCount++;
        }
    }

    protected function generateAdmissionNumber(): string
    {
        $year = now()->format('Y');

        $last = Student::withTrashed()
            ->where('admission_number', 'like', "{$year}-%")
            ->orderByDesc('admission_number')
            ->value('admission_number');

        $sequence = $last ? ((int) substr($last, 5)) + 1 : 1;

        return $year.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}