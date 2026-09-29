<?php

namespace App\Services\Tenant;

use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Collection;

class DashboardService
{
    public function stats(): array
    {
        $activeStudents = Student::where('status', 'active')->count();

        $feeCollectedThisMonth = Payment::whereMonth('paid_on', now()->month)
            ->whereYear('paid_on', now()->year)
            ->sum('amount');

        $outstandingBalance = Invoice::selectRaw('SUM(base_amount - discount_amount + fine_amount - paid_amount) as bal')->value('bal') ?? 0;

        $todayQuery = Attendance::whereDate('date', today());
        $totalToday = (clone $todayQuery)->count();
        $presentToday = (clone $todayQuery)->where('status', 'present')->count();
        $attendanceRateToday = $totalToday > 0 ? round(($presentToday / $totalToday) * 100, 1) : null;

        $overdueInvoices = Invoice::where('status', 'overdue')->count();

        return compact('activeStudents', 'feeCollectedThisMonth', 'outstandingBalance', 'attendanceRateToday', 'overdueInvoices');
    }

    public function feeCollectionTrend(int $days = 14): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $payments = Payment::where('paid_on', '>=', $start)
            ->get()
            ->groupBy(fn ($p) => $p->paid_on->format('Y-m-d'));

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $labels[] = $date->format('M d');
            $data[] = (float) round($payments->get($date->format('Y-m-d'), collect())->sum('amount'), 2);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function studentsByClass(): array
    {
        $rows = SchoolClass::withCount(['students' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('name')
            ->get();

        return [
            'labels' => $rows->pluck('name')->toArray(),
            'data' => $rows->pluck('students_count')->toArray(),
        ];
    }

    public function attendanceBreakdown(int $days = 30): array
    {
        $start = now()->subDays($days)->startOfDay();

        $counts = Attendance::where('date', '>=', $start)
            ->selectRaw('status, count(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        return [
            'present' => (int) ($counts['present'] ?? 0),
            'absent' => (int) ($counts['absent'] ?? 0),
            'late' => (int) ($counts['late'] ?? 0),
            'leave' => (int) ($counts['leave'] ?? 0),
        ];
    }

    public function topClasses(): Collection
    {
        return SchoolClass::withCount(['students' => fn ($q) => $q->where('status', 'active')])
            ->get()
            ->map(function (SchoolClass $class) {
                $class->fee_collected = Invoice::whereHas('student', fn ($q) => $q->where('school_class_id', $class->id))->sum('paid_amount');

                return $class;
            })
            ->sortByDesc('students_count')
            ->take(6);
    }
}