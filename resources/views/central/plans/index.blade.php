@extends('layouts.central')
@section('title', 'Plans')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create Plan</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('central.plans.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Plan Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Basic, Pro">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Billing Cycle</label>
                        <select name="billing_cycle" class="form-control">
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Students</label>
                        <input type="number" name="max_students" class="form-control" placeholder="Leave empty for unlimited">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Staff</label>
                        <input type="number" name="max_staff" class="form-control" placeholder="Leave empty for unlimited">
                    </div>
                    <label class="form-label d-block">Included Modules</label>
                    <p class="text-muted font-size-13">Leave all unchecked to allow every module by default.</p>
                    @foreach (config('modules') as $key => $label)
                        <div class="form-check">
                            <input type="checkbox" name="modules[]" value="{{ $key }}" class="form-check-input" id="mod-{{ $key }}">
                            <label class="form-check-label" for="mod-{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary">Save Plan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Plans</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Name</th><th>Price</th><th>Max Students</th><th>Max Staff</th></tr></thead>
                    <tbody>
                        @forelse ($plans as $plan)
                            <tr>
                                <td class="fw-semibold">{{ $plan->name }}</td>
                                <td>{{ number_format($plan->price, 2) }} / {{ $plan->billing_cycle }}</td>
                                <td>{{ $plan->max_students ?? 'Unlimited' }}</td>
                                <td>{{ $plan->max_staff ?? 'Unlimited' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No plans yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


