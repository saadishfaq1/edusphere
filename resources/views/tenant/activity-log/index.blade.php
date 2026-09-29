@extends('layouts.tenant')
@section('title', 'Activity Log')
@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h4 class="card-title mb-0">Activity Log</h4>
        <form method="GET" action="{{ route('activity-log.index') }}">
            <select name="action" class="form-control form-control-sm" onchange="this.form.submit()">
                <option value="">All Actions</option>
                @foreach ($actions as $a)
                    <option value="{{ $a }}" @selected($action === $a)>{{ ucfirst(str_replace('_', ' ', $a)) }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead><tr><th>When</th><th>User</th><th>Action</th><th>Description</th></tr></thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td><span class="badge badge-soft-info">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span></td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No activity recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $logs->links() }}</div>
    </div>
</div>
@endsection