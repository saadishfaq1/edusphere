@extends('layouts.tenant')
@section('title', 'Parent Portal Accounts')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create Parent Account</h4>
                <form method="POST" action="{{ route('guardians.store') }}" id="guardian-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>

                    <hr class="my-3">
                    <label class="form-label d-block">Find & Select Children</label>

                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <select id="filter-class" class="form-control form-control-sm">
                                <option value="">Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" @selected($schoolClassId == $class->id)>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <select id="filter-section" class="form-control form-control-sm">
                                <option value="">Section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" @selected($sectionId == $section->id)>{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <input type="text" id="filter-search" class="form-control form-control-sm" placeholder="Search name/adm#" value="{{ $search }}">
                        </div>
                    </div>

                    <div id="selected-summary" class="mb-2 small text-muted">No children selected yet.</div>

                    <div style="max-height: 220px; overflow-y: auto;" class="border rounded p-2" id="student-list">
                        @forelse ($students as $student)
                            <div class="form-check">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="form-check-input student-checkbox">
                                <label class="form-check-label">{{ $student->full_name }} ({{ $student->admission_number }})</label>
                            </div>
                        @empty
                            <div class="text-muted small py-2">No students match this filter.</div>
                        @endforelse
                    </div>
                    @error('student_ids') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror

                    <button type="submit" class="btn btn-primary mt-3">Create Account</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Parent Accounts</h4></div>
            <div class="card-body">
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <table class="table align-middle mb-0">
                    <thead><tr><th>Name</th><th>Email</th><th>Linked Children</th></tr></thead>
                    <tbody>
                        @forelse ($guardians as $guardian)
                            <tr>
                                <td>{{ $guardian->name }}</td>
                                <td>{{ $guardian->email }}</td>
                                <td>{{ $guardian->students->pluck('first_name')->join(', ') ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No parent accounts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const filterClass = document.getElementById('filter-class');
    const filterSection = document.getElementById('filter-section');
    const filterSearch = document.getElementById('filter-search');
    const selectedSummary = document.getElementById('selected-summary');

    // Remember checked students across filter reloads (so switching class/section doesn't lose earlier picks)
    let selectedIds = new Set(
        Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value)
    );

    function updateSummary() {
        selectedSummary.textContent = selectedIds.size > 0
            ? selectedIds.size + ' child' + (selectedIds.size === 1 ? '' : 'ren') + ' selected'
            : 'No children selected yet.';
    }

    function trackCheckboxes() {
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.checked = selectedIds.has(cb.value);
            cb.addEventListener('change', () => {
                if (cb.checked) selectedIds.add(cb.value);
                else selectedIds.delete(cb.value);
                updateSummary();
            });
        });
    }

    function reload() {
        const params = new URLSearchParams();
        if (filterClass.value) params.set('school_class_id', filterClass.value);
        if (filterSection.value) params.set('section_id', filterSection.value);
        if (filterSearch.value) params.set('search', filterSearch.value);
        window.location.href = '{{ route('guardians.index') }}?' + params.toString();
    }

    filterClass.addEventListener('change', () => { filterSection.value = ''; reload(); });
    filterSection.addEventListener('change', reload);

    let searchTimeout;
    filterSearch.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(reload, 500);
    });

    // Before submitting, add back any previously-selected students that aren't in the current filtered view,
    // so switching filters never silently drops earlier selections.
    document.getElementById('guardian-form').addEventListener('submit', () => {
        selectedIds.forEach(id => {
            if (!document.querySelector('.student-checkbox[value="' + id + '"]')) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'student_ids[]';
                hidden.value = id;
                document.getElementById('guardian-form').appendChild(hidden);
            }
        });
    });

    trackCheckboxes();
    updateSummary();
</script>
@endsection