
@extends('layouts.portal')
@section('title', 'My Children')
@section('content')
<h4 class="mb-4">My Children</h4>
<div class="row g-3">
    @forelse ($children as $child)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5>{{ $child->full_name }}</h5>
                    <p class="text-muted mb-3">
                        {{ $child->schoolClass->name ?? '—' }} {{ $child->section ? '- '.$child->section->name : '' }}
                        <br>Admission #: {{ $child->admission_number }}
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('portal.attendance', $child) }}" class="btn btn-sm btn-outline-primary">Attendance</a>
                        <a href="{{ route('portal.fees', $child) }}" class="btn btn-sm btn-outline-primary">Fees</a>
                        <a href="{{ route('portal.results', $child) }}" class="btn btn-sm btn-outline-primary">Results</a>
                        <a href="{{ route('portal.timetable', $child) }}" class="btn btn-sm btn-outline-primary">Timetable</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5">No children linked to your account yet. Contact the school office.</div>
    @endforelse
</div>
@endsection