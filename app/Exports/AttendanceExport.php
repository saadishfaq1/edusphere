<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected ?int $sectionId, protected ?string $from, protected ?string $to)
    {
    }

    public function collection()
    {
        return Attendance::with(['student', 'section.schoolClass'])
            ->when($this->sectionId, fn ($q) => $q->where('section_id', $this->sectionId))
            ->when($this->from, fn ($q) => $q->whereDate('date', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('date', '<=', $this->to))
            ->orderByDesc('date')
            ->get();
    }

    public function headings(): array
    {
        return ['Date', 'Student', 'Admission #', 'Class', 'Section', 'Status'];
    }

    public function map($record): array
    {
        return [
            $record->date->format('Y-m-d'),
            $record->student->full_name ?? '',
            $record->student->admission_number ?? '',
            $record->section->schoolClass->name ?? '',
            $record->section->name ?? '',
            ucfirst($record->status),
        ];
    }
}