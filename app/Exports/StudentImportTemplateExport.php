<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentImportTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['2026-0001', 'Ali', 'Khan', '2010-05-14', 'male', 'Grade 10', 'A', '12', 'ali@example.com', '03001234567', 'House 12, Street 4', 'Ahmed Khan', '03007654321', 'ahmed@example.com', 'Father', '2026-01-15', 'active'],
        ];
    }

    public function headings(): array
    {
        return [
            'Admission Number (leave blank to auto-generate)',
            'First Name', 'Last Name', 'Date of Birth (YYYY-MM-DD)', 'Gender (male/female/other)',
            'Class Name (must already exist)', 'Section Name (optional, must already exist)', 'Roll Number',
            'Email', 'Phone', 'Address',
            'Guardian Name', 'Guardian Phone', 'Guardian Email', 'Guardian Relation',
            'Admission Date (YYYY-MM-DD)', 'Status (active/inactive/graduated/transferred)',
        ];
    }
}