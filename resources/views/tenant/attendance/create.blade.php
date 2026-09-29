@extends('layouts.tenant')
@section('title', 'Mark Attendance')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Mark Attendance</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="GET" action="{{ route('attendance.create') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Class</label>
                        <select name="school_class_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected($schoolClassId == $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Section</label>
                        <select name="section_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" @selected($sectionId == $section->id)>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()">
                    </div>
                </form>

                @if ($sectionId && $roster->isNotEmpty())
                    <form method="POST" action="{{ route('attendance.store') }}">
                        @csrf
                        <input type="hidden" name="school_class_id" value="{{ $schoolClassId }}">
                        <input type="hidden" name="section_id" value="{{ $sectionId }}">
                        <input type="hidden" name="date" value="{{ $date }}">

                        <div class="table-responsive">
                            <table class="table table-centered align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Admission #</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roster as $student)
                                        <tr>
                                            <td>{{ $student->admission_number }}</td>
                                            <td>{{ $student->full_name }}</td>
                                            <td>
                                                <div class="d-flex gap-3">
                                                    @foreach (['present' => 'Present', 'absent' => 'Absent', 'late' => 'Late', 'leave' => 'Leave'] as $value => $label)
                                                        <div class="form-check">
                                                            <input type="radio" name="status[{{ $student->id }}]" value="{{ $value }}"
                                                                   class="form-check-input" id="status-{{ $student->id }}-{{ $value }}"
                                                                   @checked($student->current_status === $value)
                                                                   @if ($value === 'present' && ! $student->current_status) checked @endif>
                                                            <label class="form-check-label" for="status-{{ $student->id }}-{{ $value }}">{{ $label }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save Attendance</button>
                        </div>
                    </form>
                @elseif ($sectionId)
                    <div class="text-center text-muted py-4">No active students found in this section.</div>
                @else
                    <div class="text-center text-muted py-4">Select a Class, Section, and Date to mark attendance.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

