@extends('layouts.tenant')
@section('title', 'Enter Marks')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">{{ $examSubject->exam->name }} — {{ $examSubject->subject->name }} (out of {{ $examSubject->max_marks }})</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('exams.marks.save', $examSubject) }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-centered align-middle table-nowrap mb-0">
                            <thead><tr><th>Admission #</th><th>Name</th><th>Marks Obtained</th></tr></thead>
                            <tbody>
                                @foreach ($roster as $student)
                                    <tr>
                                        <td>{{ $student->admission_number }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td style="max-width: 120px;">
                                            <input type="number" name="marks[{{ $student->id }}]" class="form-control"
                                                   value="{{ $student->obtained_marks }}" min="0" max="{{ $examSubject->max_marks }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Marks</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

