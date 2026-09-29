@extends('layouts.tenant')
@section('title', 'Sections')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Sections</h4>
                <a href="{{ route('sections.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Add Section
                </a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-centered align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Capacity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sections as $section)
                                <tr>
                                    <td>{{ $section->schoolClass->name }}</td>
                                    <td class="fw-semibold">{{ $section->name }}</td>
                                    <td>{{ $section->capacity ?? '—' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('sections.edit', $section) }}">Edit</a>
                                                <form method="POST" action="{{ route('sections.destroy', $section) }}" onsubmit="return confirm('Delete this section?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No sections yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

