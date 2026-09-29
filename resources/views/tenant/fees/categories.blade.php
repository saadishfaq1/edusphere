@extends('layouts.tenant')
@section('title', 'Fee Categories')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Fee Category</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('fees.categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Tuition Fee">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-control">
                            <option value="recurring">Recurring (e.g. monthly tuition)</option>
                            <option value="one_time">One-time (e.g. admission, exam fee)</option>
                        </select>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_transport" value="1" class="form-check-input" id="is_transport">
                        <label class="form-check-label" for="is_transport">This is a Transport fee (amount comes from each student's assigned route, not a flat class amount)</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Categories</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Name</th><th>Type</th></tr></thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td><span class="badge badge-soft-info">{{ $category->type === 'recurring' ? 'Recurring' : 'One-time' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-4">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

