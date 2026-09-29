@extends('layouts.tenant')
@section('title', 'Scholarships')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Scholarship</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('fees.scholarships.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Merit Scholarship">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Discount Type</label>
                        <select name="discount_type" class="form-control">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Value</label>
                        <input type="number" step="0.01" name="discount_value" class="form-control" placeholder="e.g. 25 (for 25%) or 2000">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Scholarships</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Name</th><th>Discount</th></tr></thead>
                    <tbody>
                        @forelse ($scholarships as $scholarship)
                            <tr>
                                <td>{{ $scholarship->name }}</td>
                                <td>{{ $scholarship->discount_type === 'percentage' ? $scholarship->discount_value.'%' : number_format($scholarship->discount_value, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-4">No scholarships yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

