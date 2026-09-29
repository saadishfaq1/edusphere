@extends('layouts.tenant')
@section('title', 'Examinations')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create Exam</h4>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('examinations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Exam Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Mid Term 2026">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <select name="school_class_id" class="form-control @error('school_class_id') is-invalid @enderror">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('school_class_id') == $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('school_class_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                            @error('start_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                            @error('end_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Exam</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Exams</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Exam</th><th>Class</th><th>Dates</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($exams as $exam)
                            <tr>
                                <td class="fw-semibold">{{ $exam->name }}</td>
                                <td>{{ $exam->schoolClass->name }}</td>
                                <td>{{ $exam->start_date->format('d M') }} - {{ $exam->end_date->format('d M Y') }}</td>
                                <td><a href="{{ route('examinations.show', $exam) }}" class="btn btn-sm btn-soft-primary">Manage</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No exams yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

