@extends('layouts.tenant')
@section('title', 'Classes')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Classes</h4>
                <a href="{{ route('classes.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Add Class
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
                                <th>Sections</th>
                                <th>Sort Order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($classes as $class)
                                <tr>
                                    <td class="fw-semibold">{{ $class->name }}</td>
                                    <td>
                                        @forelse ($class->sections as $section)
                                            <span class="badge badge-soft-primary font-size-12 me-1">{{ $section->name }}</span>
                                        @empty
                                            <span class="text-muted">No sections yet</span>
                                        @endforelse
                                    </td>
                                    <td>{{ $class->sort_order }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('classes.edit', $class) }}">Edit</a>
                                                <form method="POST" action="{{ route('classes.destroy', $class) }}" onsubmit="return confirm('Delete this class? Its sections will also be deleted.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No classes yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

