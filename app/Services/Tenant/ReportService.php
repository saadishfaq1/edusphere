<?php

namespace App\Services\Tenant;

use App\Models\Attendance;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Invoice;
use App\Models\SchoolClass;
use App\Models\Student;

class ReportService
{
    public function studentCounts(): array
    {
        return [
            'total' => Student::where('status', 'active')->count(),
            'by_class' => SchoolClass::withCount(['students' => fn ($q) => $q->where('status', 'active')])->get(),
        ];
    }

    public function feeSummary(): array
    {
        return [
            'total_invoiced' => Invoice::sum('base_amount'),
            'total_collected' => Invoice::sum('paid_amount'),
            'total_outstanding' => Invoice::selectRaw('SUM(base_amount - discount_amount + fine_amount - paid_amount) as balance')->value('balance') ?? 0,
            'overdue_count' => Invoice::where('status', 'overdue')->count(),
        ];
    }

    public function attendanceSummary(?string $from = null, ?string $to = null): array
    {
        $query = Attendance::when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to));

        $total = (clone $query)->count();
        $present = (clone $query)->where('status', 'present')->count();

        return [
            'total_marked' => $total,
            'present' => $present,
            'absent' => (clone $query)->where('status', 'absent')->count(),
            'late' => (clone $query)->where('status', 'late')->count(),
            'leave' => (clone $query)->where('status', 'leave')->count(),
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
        ];
    }

    public function examSummary()
    {
        return Exam::withCount([
            'examSubjects as results_entered_count' => fn ($q) => $q->whereHas('results', fn ($r) => $r->whereNotNull('obtained_marks')),
        ])->with('schoolClass')->latest()->take(5)->get();
    }
}