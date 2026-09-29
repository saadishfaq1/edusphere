@extends('layouts.portal')
@section('title', 'Timetable')
@section('content')
<a href="{{ route('portal.dashboard') }}" class="btn btn-sm btn-light mb-3"><i class="bi bi-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h5 class="mb-0">{{ $student->full_name }} — Timetable</h5></div>
    <div class="card-body">
        @if ($student->section_id)
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead><tr>@foreach ($days as $day)<th class="text-center">{{ ucfirst($day) }}</th>@endforeach</tr></thead>
                    <tbody>
                        <tr>
                            @foreach ($days as $day)
                                <td style="vertical-align: top; min-width: 130px;">
                                    @forelse ($slots->get($day, collect()) as $slot)
                                        <div class="border rounded p-2 mb-2 bg-light">
                                            <div class="fw-semibold small">{{ $slot->subject->name }}</div>
                                            <div class="text-muted" style="font-size: 11px;">
                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                            </div>
                                        </div>
                                    @empty
                                        <span class="text-muted small">—</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted py-4">This student isn't assigned to a section yet.</div>
        @endif
    </div>
</div>
@endsection