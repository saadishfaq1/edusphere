@extends('layouts.central')
@section('title', 'Schools')
@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h4 class="card-title mb-0">Schools</h4>
        <a href="{{ route('central.schools.create') }}" class="btn btn-primary">Onboard New School</a>
    </div>
    <div class="card-body">
        {{-- @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif --}}
        <table class="table table-centered align-middle table-nowrap mb-0">
            <thead><tr><th>Name</th><th>Subdomain</th><th>Plan</th><th>Users</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($schools as $school)
                    <tr>
                        <td class="fw-semibold">{{ $school->name }}</td>
                        <td>{{ $school->subdomain }}</td>
                        <td>{{ $school->plan->name ?? 'No plan' }}</td>
                        <td>{{ $school->users_count }}</td>
                        <td>
                            @php $colors = ['trial' => 'warning', 'active' => 'success', 'suspended' => 'danger']; @endphp
                            <span class="badge badge-soft-{{ $colors[$school->status] }}">{{ ucfirst($school->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('central.schools.show', $school) }}" class="btn btn-sm btn-soft-primary">Manage</a>
                            <button type="button" class="btn btn-sm btn-soft-danger"
                                onclick="confirmDelete('{{ route('central.schools.destroy', $school) }}', 'Delete {{ $school->name }}?', 'This permanently removes all its students, staff, fees, and records. This cannot be undone.')">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No schools yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


