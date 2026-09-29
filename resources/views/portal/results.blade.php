@extends('layouts.portal')
@section('title', 'Results')
@section('content')
<a href="{{ route('portal.dashboard') }}" class="btn btn-sm btn-light mb-3"><i class="bi bi-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h5 class="mb-0">{{ $student->full_name }} — Exams</h5></div>
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead><tr><th>Exam</th><th>Dates</th><th></th></tr></thead>
            <tbody>
                @forelse ($exams as $exam)
                    <tr>
                        <td>{{ $exam->name }}</td>
                        <td>{{ $exam->start_date->format('d M') }} - {{ $exam->end_date->format('d M Y') }}</td>
                        <td><a href="{{ route('portal.report-card', [$student, $exam]) }}" class="btn btn-sm btn-outline-primary">View Report Card</a></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No exams recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection