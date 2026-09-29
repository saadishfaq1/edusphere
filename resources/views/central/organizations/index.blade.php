@extends('layouts.central')
@section('title', 'Organizations')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create Organization</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('central.organizations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Organization Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Greenwood Education Group">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Organization</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Organizations</h4></div>
            <div class="card-body">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Name</th><th>Branches</th><th>Contact</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($organizations as $org)
                            <tr>
                                <td class="fw-semibold">{{ $org->name }}</td>
                                <td>{{ $org->schools_count }}</td>
                                <td>{{ $org->contact_email ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('central.organizations.show', $org) }}" class="btn btn-sm btn-soft-primary">View</a>
                                    <button type="button" class="btn btn-sm btn-soft-danger"
                                        onclick="confirmDelete('{{ route('central.organizations.destroy', $org) }}', 'Delete {{ $org->name }}?', 'This cannot be undone.')">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No organizations yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection