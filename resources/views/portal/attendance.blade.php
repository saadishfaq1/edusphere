@extends('layouts.portal')
@section('title', 'Attendance')
@section('content')
<a href="{{ route('portal.dashboard') }}" class="btn btn-sm btn-light mb-3"><i class="bi bi-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h5 class="mb-0">{{ $student->full_name }} — Attendance</h5></div>
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead><tr><th>Date</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($records as $record)
                    @php $colors = ['present' => 'success', 'absent' => 'danger', 'late' => 'warning', 'leave' => 'info']; @endphp
                    <tr>
                        <td>{{ $record->date->format('d M Y') }}</td>
                        <td><span class="badge bg-{{ $colors[$record->status] }}">{{ ucfirst($record->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted py-4">No attendance records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection