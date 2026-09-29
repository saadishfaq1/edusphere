@extends('layouts.tenant')
@section('title', $exam->name)
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Subject to {{ $exam->name }}</h4>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('examinations.subjects.attach', $exam) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <select name="subject_id" class="form-control">
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Max Marks</label>
                            <input type="number" name="max_marks" class="form-control" value="100">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Passing Marks</label>
                            <input type="number" name="passing_marks" class="form-control" value="40">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Subjects in this Exam</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Subject</th><th>Max Marks</th><th>Passing</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($exam->examSubjects as $examSubject)
                            <tr>
                                <td>{{ $examSubject->subject->name }}</td>
                                <td>{{ $examSubject->max_marks }}</td>
                                <td>{{ $examSubject->passing_marks }}</td>
                                <td><a href="{{ route('exams.marks', $examSubject) }}" class="btn btn-sm btn-soft-primary">Enter Marks</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No subjects added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Students — {{ $exam->schoolClass->name }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Admission #</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td>{{ $student->admission_number }}</td>
                                    <td>{{ $student->full_name }}</td>
                                    <td>
                                        <a href="{{ route('examinations.report-card', [$exam, $student]) }}" class="btn btn-sm btn-soft-primary">
                                            <i class="bx bx-file"></i> Report Card
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">No students in this class.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

