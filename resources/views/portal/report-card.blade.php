@extends('layouts.portal')
@section('title', 'Report Card')
@section('content')
<a href="{{ route('portal.results', $student) }}" class="btn btn-sm btn-light mb-3"><i class="bi bi-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h5 class="mb-0">{{ $student->full_name }} — {{ $exam->name }} Report Card</h5></div>
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead><tr><th>Subject</th><th>Marks Obtained</th><th>Max Marks</th><th>Status</th></tr></thead>
            <tbody>
                @foreach ($results as $result)
                    @php $passed = $result->obtained_marks !== null && $result->obtained_marks >= $result->passing_marks; @endphp
                    <tr>
                        <td>{{ $result->subject->name }}</td>
                        <td>{{ $result->obtained_marks ?? '—' }}</td>
                        <td>{{ $result->max_marks }}</td>
                        <td>
                            @if ($result->obtained_marks === null)
                                <span class="badge bg-secondary">Not Entered</span>
                            @else
                                <span class="badge bg-{{ $passed ? 'success' : 'danger' }}">{{ $passed ? 'Pass' : 'Fail' }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection