<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Student::with(['schoolClass', 'section'])->orderBy('first_name')->get();
    }

    public function headings(): array
    {
        return ['Admission #', 'Name', 'Class', 'Section', 'Gender', 'DOB', 'Phone', 'Guardian Name', 'Guardian Phone', 'Status'];
    }

    public function map($student): array
    {
        return [
            $student->admission_number,
            $student->full_name,
            $student->schoolClass->name ?? '',
            $student->section->name ?? '',
            ucfirst($student->gender),
            $student->date_of_birth->format('Y-m-d'),
            $student->phone,
            $student->guardian_name,
            $student->guardian_phone,
            ucfirst($student->status),
        ];
    }
}