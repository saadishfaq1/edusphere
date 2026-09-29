@extends('layouts.tenant')
@section('title', 'Reports')
@section('content')
<div class="row mb-2">
    <div class="col-12">
        <form method="GET" class="d-flex gap-2 justify-content-end">
            <input type="date" name="from" value="{{ $from }}" class="form-control" style="max-width:180px;">
            <input type="date" name="to" value="{{ $to }}" class="form-control" style="max-width:180px;">
            <button class="btn btn-primary">Filter Period</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Income</p>
                <h4 class="text-success">{{ number_format($summary['total_income'], 2) }}</h4>
                <small class="text-muted">Fees: {{ number_format($summary['fee_collection'], 2) }} + Other: {{ number_format($summary['other_income'], 2) }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Expenses</p>
                <h4 class="text-danger">{{ number_format($summary['total_expenses'], 2) }}</h4>
                <small class="text-muted">Operating: {{ number_format($summary['operating_expenses'], 2) }} + Payroll: {{ number_format($summary['payroll_paid'], 2) }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Net Profit / Loss</p>
                <h4 class="{{ $summary['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($summary['net_profit'], 2) }}
                </h4>
                <small class="text-muted">{{ $summary['net_profit'] >= 0 ? 'Profit' : 'Loss' }} for selected period</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Active Students</p>
                <h4>{{ $studentCounts['total'] }}</h4>
                <small class="text-muted">Across all classes</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Fee Collection Overview</h5></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><td>Total Invoiced</td><td class="text-end">{{ number_format($feeSummary['total_invoiced'], 2) }}</td></tr>
                    <tr><td>Total Collected</td><td class="text-end text-success">{{ number_format($feeSummary['total_collected'], 2) }}</td></tr>
                    <tr><td>Outstanding Balance</td><td class="text-end text-danger">{{ number_format($feeSummary['total_outstanding'], 2) }}</td></tr>
                    <tr><td>Overdue Invoices</td><td class="text-end">{{ $feeSummary['overdue_count'] }}</td></tr>
                </table>
                <a href="{{ route('fees.invoices') }}" class="btn btn-sm btn-soft-primary mt-3">View All Invoices</a>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Attendance Overview (selected period)</h5></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><td>Attendance Rate</td><td class="text-end fw-bold">{{ $attendanceSummary['attendance_rate'] }}%</td></tr>
                    <tr><td>Present</td><td class="text-end text-success">{{ $attendanceSummary['present'] }}</td></tr>
                    <tr><td>Absent</td><td class="text-end text-danger">{{ $attendanceSummary['absent'] }}</td></tr>
                    <tr><td>Late</td><td class="text-end text-warning">{{ $attendanceSummary['late'] }}</td></tr>
                    <tr><td>Leave</td><td class="text-end text-info">{{ $attendanceSummary['leave'] }}</td></tr>
                </table>
                <a href="{{ route('attendance.report') }}" class="btn btn-sm btn-soft-primary mt-3">View Full Attendance Report</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Students per Class</h5></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Class</th><th class="text-end">Students</th></tr></thead>
                    <tbody>
                        @forelse ($studentCounts['by_class'] as $class)
                            <tr><td>{{ $class->name }}</td><td class="text-end">{{ $class->students_count }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">No classes yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Recent Exams</h5></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Exam</th><th>Class</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($examSummary as $exam)
                            <tr>
                                <td>{{ $exam->name }}</td>
                                <td>{{ $exam->schoolClass->name }}</td>
                                <td><a href="{{ route('examinations.show', $exam) }}" class="btn btn-sm btn-soft-primary">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">No exams yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

