@extends('layouts.tenant')
@section('title', 'My Schedule')
@section('content')
<div class="card">
    <div class="card-header"><h4 class="card-title mb-0">My Weekly Schedule</h4></div>
    <div class="card-body">
        @if (! $employee)
            <div class="alert alert-warning">
                Your login isn't linked to a staff record yet, so no schedule can be shown. Ask your School Admin to link your account under HR / Staff → Employees.
            </div>
        @elseif ($slots->isEmpty())
            <div class="text-center text-muted py-4">No periods assigned to you yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            @foreach ($days as $day)
                                <th class="text-center">{{ ucfirst($day) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($days as $day)
                                <td style="vertical-align: top; min-width: 160px;">
                                    @forelse ($slots->get($day, collect()) as $slot)
                                        <div class="border rounded p-2 mb-2 bg-light-subtle">
                                            <div class="fw-semibold font-size-13">{{ $slot->subject->name }}</div>
                                            <div class="text-muted font-size-12">
                                                {{ $slot->section->schoolClass->name ?? '' }} {{ $slot->section ? '- '.$slot->section->name : '' }}
                                            </div>
                                            <div class="text-muted font-size-12">
                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                            </div>
                                        </div>
                                    @empty
                                        <span class="text-muted font-size-12">—</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection