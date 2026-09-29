@extends('layouts.tenant')
@section('title', 'Timetable')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Timetable</h4>
            </div>
            <div class="card-body">
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                <form method="GET" action="{{ route('timetable.index') }}" class="mb-4" style="max-width: 300px;">
                    <label class="form-label">Section</label>
                    <select name="section_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Select Section</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}" @selected($sectionId == $section->id)>{{ $section->full_name }}</option>
                        @endforeach
                    </select>
                </form>

                @if ($sectionId)
                    <div class="row mb-4">
                        <div class="col-lg-5">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Class Period</h5>
                                    <form method="POST" action="{{ route('timetable.store') }}">
                                        @csrf
                                        <input type="hidden" name="section_id" value="{{ $sectionId }}">
                                        <div class="mb-3">
                                            <label class="form-label">Day</label>
                                            <select name="day" class="form-control">
                                                @foreach ($days as $day)
                                                    <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Subject</label>
                                            <select name="subject_id" class="form-control">
                                                <option value="">Select Subject</option>
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Teacher (optional)</label>
                                            <select name="employee_id" class="form-control">
                                                <option value="">Unassigned</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label class="form-label">Start Time</label>
                                                <input type="time" name="start_time" class="form-control">
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label class="form-label">End Time</label>
                                                <input type="time" name="end_time" class="form-control">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add to Timetable</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="table-responsive">
                                <table class="table table-bordered table-centered align-middle mb-0">
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
                                                <td style="vertical-align: top; min-width: 140px;">
                                                    @forelse ($slots->get($day, collect()) as $slot)
                                                        <div class="border rounded p-2 mb-2 bg-light-subtle">
                                                            <div class="fw-semibold font-size-13">{{ $slot->subject->name }}</div>
                                                            <div class="text-muted font-size-12">
                                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} -
                                                                {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                                            </div>
                                                            @if ($slot->employee)
                                                                <div class="text-muted font-size-12">{{ $slot->employee->full_name }}</div>
                                                            @endif
                                                            <form method="POST" action="{{ route('timetable.destroy', $slot) }}" class="mt-1">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-soft-danger py-0 px-1 font-size-11">Remove</button>
                                                            </form>
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
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-4">Select a section to view or build its timetable.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

