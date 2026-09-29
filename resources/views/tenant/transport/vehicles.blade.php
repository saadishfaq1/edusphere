@extends('layouts.tenant')
@section('title', 'Vehicles')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Vehicle</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('transport.vehicles.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Vehicle Number</label>
                        <input type="text" name="vehicle_number" class="form-control @error('vehicle_number') is-invalid @enderror" value="{{ old('vehicle_number') }}" placeholder="e.g. BUS-01">
                        @error('vehicle_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Driver Name</label>
                        <input type="text" name="driver_name" class="form-control" value="{{ old('driver_name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Driver Phone</label>
                        <input type="text" name="driver_phone" class="form-control" value="{{ old('driver_phone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" value="{{ old('capacity', 20) }}" min="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Vehicle</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Vehicles</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Vehicle #</th><th>Driver</th><th>Phone</th><th>Capacity</th></tr></thead>
                    <tbody>
                        @forelse ($vehicles as $vehicle)
                            <tr>
                                <td class="fw-semibold">{{ $vehicle->vehicle_number }}</td>
                                <td>{{ $vehicle->driver_name ?? '—' }}</td>
                                <td>{{ $vehicle->driver_phone ?? '—' }}</td>
                                <td>{{ $vehicle->capacity }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No vehicles yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

