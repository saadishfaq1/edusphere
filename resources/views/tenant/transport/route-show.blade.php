@extends('layouts.tenant')
@section('title', $route->name)
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Stop to {{ $route->name }}</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('transport.routes.stops.store', $route) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Stop Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Main Market">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pickup Time</label>
                        <input type="time" name="pickup_time" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Stop</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Stops on this Route</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>#</th><th>Stop</th><th>Pickup Time</th></tr></thead>
                    <tbody>
                        @forelse ($route->stops as $i => $stop)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $stop->name }}</td>
                                <td>{{ $stop->pickup_time }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No stops added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

