@extends('layouts.tenant')
@section('title', 'Parent Portal Accounts')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create Parent Account</h4>
                <form method="POST" action="{{ route('guardians.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Children</label>
                        @foreach ($students as $student)
                            <div class="form-check">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="form-check-input">
                                <label class="form-check-label">{{ $student->full_name }} ({{ $student->admission_number }})</label>
                            </div>
                        @endforeach
                        @error('student_ids') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Parent Accounts</h4></div>
            <div class="card-body">
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <table class="table align-middle mb-0">
                    <thead><tr><th>Name</th><th>Email</th><th>Linked Children</th></tr></thead>
                    <tbody>
                        @forelse ($guardians as $guardian)
                            <tr>
                                <td>{{ $guardian->name }}</td>
                                <td>{{ $guardian->email }}</td>
                                <td>{{ $guardian->students->pluck('first_name')->join(', ') ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No parent accounts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection