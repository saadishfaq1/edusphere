@extends('layouts.tenant')
@section('title', 'Report Card')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">{{ $student->full_name }} — {{ $exam->name }} Report Card</h4>
            </div>
            <div class="card-body">
                <a href="{{ route('examinations.show', $exam) }}" class="btn btn-sm btn-light mb-3">
                    <i class="bx bx-arrow-back"></i> Back to {{ $exam->name }}
                </a>
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Subject</th><th>Marks Obtained</th><th>Max Marks</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach ($results as $result)
                            @php
                                $passed = $result->obtained_marks !== null && $result->obtained_marks >= $result->passing_marks;
                            @endphp
                            <tr>
                                <td>{{ $result->subject->name }}</td>
                                <td>{{ $result->obtained_marks ?? '—' }}</td>
                                <td>{{ $result->max_marks }}</td>
                                <td>
                                    @if ($result->obtained_marks === null)
                                        <span class="badge badge-soft-secondary">Not Entered</span>
                                    @else
                                        <span class="badge badge-soft-{{ $passed ? 'success' : 'danger' }}">
                                            {{ $passed ? 'Pass' : 'Fail' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

