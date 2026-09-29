@extends('layouts.tenant')

@section('title', 'Students')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h4 class="card-title mb-0">Students</h4>
                <div class="d-flex gap-2">
                    <form method="GET" action="{{ route('students.index') }}" class="search-box">
                        <div class="position-relative">
                            <input type="text" name="search" value="{{ $search }}" class="form-control rounded bg-light border-0" placeholder="Search...">
                            <i class="bx bx-search search-icon"></i>
                        </div>
                    </form>
                    <a href="{{ route('students.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add Student
                    </a>
                    <a href="{{ route('students.import') }}" class="btn btn-soft-secondary"><i class="bi bi-upload"></i> Bulk Import</a>
                    <a href="{{ route('students.export') }}" class="btn btn-soft-secondary"><i class="bi bi-download"></i> Export CSV</a>
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-centered align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Admission #</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Class</th>
                                <th>Admission Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td class="fw-semibold">{{ $student->admission_number }}</td>
                                    <td>{{ $student->full_name }}</td>
                                    <td class="text-capitalize">{{ $student->gender }}</td>
                                    <td>{{ $student->schoolClass?->name }} {{ $student->section ? '- '.$student->section->name : '' }}</td>
                                    <td>{{ $student->admission_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-soft-{{ $student->status === 'active' ? 'success' : 'secondary' }} font-size-12">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('students.edit', $student) }}">Edit</a>
                                                <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No students found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

