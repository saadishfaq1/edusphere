@extends('layouts.tenant')
@section('title', 'School Profile')
@section('content')
<div class="row">
    <div class="col-lg-3">
        @include('tenant.settings._nav')
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">School Profile</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('settings.profile.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">School Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $school->name) }}">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $school->email) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $school->phone) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $school->address) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <input type="text" name="academic_year" class="form-control" value="{{ old('academic_year', $school->academic_year) }}" placeholder="e.g. 2026-2027">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

