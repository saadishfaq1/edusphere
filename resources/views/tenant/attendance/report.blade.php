@extends('layouts.tenant')
@section('title', 'Attendance Report')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Attendance Report</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('attendance.report') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Section</label>
                        <select name="section_id" class="form-control">
                            <option value="">All Sections</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" @selected($sectionId == $section->id)>{{ $section->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">From</label>
                        <input type="date" name="from" value="{{ $from }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To</label>
                        <input type="date" name="to" value="{{ $to }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('attendance.report.export') }}?section_id={{ $sectionId }}&from={{ $from }}&to={{ $to }}" class="btn btn-secondary"><i class="bi bi-download"></i> Export CSV</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-centered align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Class - Section</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $record)
                                <tr>
                                    <td>{{ $record->date->format('d M Y') }}</td>
                                    <td>{{ $record->student->full_name }}</td>
                                    <td>{{ $record->section->full_name }}</td>
                                    <td>
                                        @php
                                            $colors = ['present' => 'success', 'absent' => 'danger', 'late' => 'warning', 'leave' => 'info'];
                                        @endphp
                                        <span class="badge badge-soft-{{ $colors[$record->status] }} font-size-12">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No attendance records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

