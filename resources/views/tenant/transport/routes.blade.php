@extends('layouts.tenant')
@section('title', 'Routes')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Route</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('transport.routes.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Route Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. North Zone Route">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vehicle</label>
                        <select name="vehicle_id" class="form-control">
                            <option value="">Unassigned</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monthly Fee</label>
                        <input type="number" step="0.01" name="monthly_fee" class="form-control" placeholder="e.g. 2000">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Route</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Routes</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Route</th><th>Vehicle</th><th>Monthly Fee</th><th>Stops</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($routes as $route)
                            <tr>
                                <td class="fw-semibold">{{ $route->name }}</td>
                                <td>{{ $route->vehicle->vehicle_number ?? '—' }}</td>
                                <td>{{ number_format($route->monthly_fee, 2) }}</td>
                                <td>{{ $route->stops->count() }}</td>
                                <td><a href="{{ route('transport.routes.show', $route) }}" class="btn btn-sm btn-soft-primary">Manage Stops</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No routes yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

