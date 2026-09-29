@extends('layouts.central')
@section('title', $organization->name)
@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-label">Branches</div>
            <div class="stat-value">{{ $summary['branch_count'] }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-label">Total Students (all branches)</div>
            <div class="stat-value">{{ number_format($summary['total_students']) }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title mb-0">Branches</h4></div>
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead><tr><th>School</th><th>Branch</th><th>Subdomain</th><th>Plan</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($summary['schools'] as $school)
                    <tr>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->branch_name ?? '—' }}</td>
                        <td>{{ $school->subdomain }}</td>
                        <td>{{ $school->plan->name ?? 'No plan' }}</td>
                        <td>
                            @php $colors = ['trial' => 'warning', 'active' => 'success', 'suspended' => 'danger']; @endphp
                            <span class="badge-soft-{{ $colors[$school->status] }}">{{ ucfirst($school->status) }}</span>
                        </td>
                        <td><a href="{{ route('central.schools.show', $school) }}" class="btn btn-sm btn-soft-primary">Manage</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No branches yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection