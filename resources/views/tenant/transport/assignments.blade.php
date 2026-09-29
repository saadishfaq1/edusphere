@extends('layouts.tenant')
@section('title', 'Transport Assignments')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Assign Student to a Stop</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('transport.assignments.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-control">
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->full_name }} ({{ $student->admission_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stop</label>
                        <select name="transport_stop_id" class="form-control">
                            <option value="">No Transport</option>
                            @foreach ($routes as $route)
                                <optgroup label="{{ $route->name }}">
                                    @foreach ($route->stops as $stop)
                                        <option value="{{ $stop->id }}">{{ $stop->name }} ({{ $stop->pickup_time }})</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Assignment</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Students & Their Stops</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Student</th><th>Route</th><th>Stop</th></tr></thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>{{ $student->full_name }}</td>
                                <td>{{ $student->transportStop->route->name ?? '—' }}</td>
                                <td>{{ $student->transportStop->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No students yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

