@extends('layouts.tenant')
@section('title', 'Student Promotion')
@section('content')

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Select Students to Promote</h4></div>
            <div class="card-body">
                <form method="GET" action="{{ route('promotion.index') }}" class="row g-2 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">From Class</label>
                        <select name="from_class_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected($fromClassId == $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">From Section (optional)</label>
                        <select name="from_section_id" class="form-control" onchange="this.form.submit()">
                            <option value="">All Sections</option>
                            @foreach ($fromSections as $section)
                                <option value="{{ $section->id }}" @selected($fromSectionId == $section->id)>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if ($fromClassId)
                    @if ($roster->isNotEmpty())
                        <form method="POST" action="{{ route('promotion.promote') }}" id="promotion-form">
                            @csrf

                            <div class="table-responsive mb-3">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th>
                                            <th>Admission #</th>
                                            <th>Name</th>
                                            <th>Current Class</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roster as $student)
                                            <tr>
                                                <td><input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-check"></td>
                                                <td>{{ $student->admission_number }}</td>
                                                <td>{{ $student->full_name }}</td>
                                                <td>{{ $student->schoolClass->name }} {{ $student->section ? '- '.$student->section->name : '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Action</label>
                                    <select name="action" id="action-select" class="form-control">
                                        <option value="promote">Promote to another class</option>
                                        <option value="graduate">Graduate (final class)</option>
                                    </select>
                                </div>
                                <div class="col-md-3" id="to-class-wrap">
                                    <label class="form-label">To Class</label>
                                    <select name="to_class_id" id="to-class" class="form-control">
                                        <option value="">Select Class</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3" id="to-section-wrap">
                                    <label class="form-label">To Section (optional)</label>
                                    <select name="to_section_id" id="to-section" class="form-control">
                                        <option value="">No Section</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Are you sure? This will move the selected students immediately.');">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center text-muted py-4">No active students found in this class/section.</div>
                    @endif
                @else
                    <div class="text-center text-muted py-4">Select a class to see students eligible for promotion.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Recent Promotion History</h4></div>
            <div class="card-body">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Student</th><th>From</th><th>To</th><th>Action</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse ($history as $log)
                            <tr>
                                <td>{{ $log->student->full_name ?? 'Deleted student' }}</td>
                                <td>{{ $log->fromSchoolClass->name ?? '—' }}</td>
                                <td>{{ $log->toSchoolClass->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $log->action === 'graduated' ? 'success' : 'info' }}">{{ ucfirst($log->action) }}</span>
                                </td>
                                <td>{{ $log->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No promotion history yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('select-all')?.addEventListener('change', function () {
        document.querySelectorAll('.student-check').forEach(cb => cb.checked = this.checked);
    });

    const actionSelect = document.getElementById('action-select');
    const toClassWrap = document.getElementById('to-class-wrap');
    const toSectionWrap = document.getElementById('to-section-wrap');
    const toClass = document.getElementById('to-class');
    const toSection = document.getElementById('to-section');

    actionSelect.addEventListener('change', function () {
        const isGraduate = this.value === 'graduate';
        toClassWrap.style.display = isGraduate ? 'none' : 'block';
        toSectionWrap.style.display = isGraduate ? 'none' : 'block';
        toClass.required = !isGraduate;
    });

    toClass.addEventListener('change', function () {
        toSection.innerHTML = '<option value="">No Section</option>';
        if (!this.value) return;

        fetch('{{ route('promotion.sections-for') }}?class_id=' + this.value)
            .then(res => res.json())
            .then(sections => {
                sections.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.name;
                    toSection.appendChild(opt);
                });
            });
    });
</script>
@endsection