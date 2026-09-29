@extends('layouts.tenant')
@section('title', 'Dashboard')

@section('content')

<div class="row g-3 mb-3">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Active Students</div>
                    <div class="stat-value">{{ number_format($stats['activeStudents']) }}</div>
                </div>
                <div class="stat-icon bg-primary-soft text-primary"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Fee Collected (This Month)</div>
                    <div class="stat-value">{{ number_format($stats['feeCollectedThisMonth'], 0) }}</div>
                </div>
                <div class="stat-icon bg-success-soft text-success"><i class="bi bi-cash-coin"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Outstanding Balance</div>
                    <div class="stat-value">{{ number_format($stats['outstandingBalance'], 0) }}</div>
                    <div class="stat-trend {{ $stats['overdueInvoices'] > 0 ? 'down' : '' }}">
                        {{ $stats['overdueInvoices'] }} overdue invoice{{ $stats['overdueInvoices'] === 1 ? '' : 's' }}
                    </div>
                </div>
                <div class="stat-icon bg-danger-soft text-danger"><i class="bi bi-exclamation-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Attendance Today</div>
                    <div class="stat-value">{{ $stats['attendanceRateToday'] !== null ? $stats['attendanceRateToday'].'%' : '—' }}</div>
                    <div class="stat-trend">{{ $stats['attendanceRateToday'] !== null ? 'Marked today' : 'Not marked yet' }}</div>
                </div>
                <div class="stat-icon bg-info-soft text-info"><i class="bi bi-calendar-check"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header"><h2 class="card-title mb-0">Fee Collection (Last 14 Days)</h2></div>
            <div class="card-body">
                <div data-chart="area"
                     data-series='[{"name":"Collected","data":{{ json_encode($feeTrend['data']) }}}]'
                     data-cats='{{ json_encode($feeTrend['labels']) }}'
                     data-colors='["#14C38E"]'
                     data-height="300"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header"><h2 class="card-title mb-0">Students by Class</h2></div>
            <div class="card-body">
                @if (array_sum($studentsByClass['data']) > 0)
                    <div data-chart="donut"
                         data-series='{{ json_encode($studentsByClass['data']) }}'
                         data-labels='{{ json_encode($studentsByClass['labels']) }}'
                         data-colors='["#14C38E","#0EA5E9","#F59E0B","#8B5CF6","#EF4444","#EC4899","#6366F1"]'
                         data-height="240"></div>
                @else
                    <div class="text-center text-muted py-5">No students enrolled in any class yet.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header"><h2 class="card-title mb-0">Attendance (Last 30 Days)</h2></div>
            <div class="card-body">
                <div data-chart="bar"
                     data-series='[{"name":"Days","data":[{{ $attendanceBreakdown['present'] }},{{ $attendanceBreakdown['absent'] }},{{ $attendanceBreakdown['late'] }},{{ $attendanceBreakdown['leave'] }}]}]'
                     data-cats='["Present","Absent","Late","Leave"]'
                     data-colors='["#14C38E"]'
                     data-height="240"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header"><h2 class="card-title mb-0">Top Classes</h2></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Class</th><th>Students</th><th>Fee Collected</th></tr></thead>
                    <tbody>
                        @forelse ($topClasses as $class)
                            <tr>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->students_count }}</td>
                                <td>{{ number_format($class->fee_collected, 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No classes yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.js"></script>
<script src="{{ asset('assets-elite/js/charts.js') }}"></script>
@endpush