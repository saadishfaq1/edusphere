@extends('layouts.tenant')
@section('title', 'My Profile')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">My Profile</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

                <form method="POST" action="{{ route('my-profile.update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4 text-center">
                        @if ($user->photo_path)
                            <img src="{{ asset('storage/'.$user->photo_path) }}" class="rounded-circle border" width="80" height="80" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center border" style="width: 80px; height: 80px;">
                                <i class="bi bi-person fs-2 text-muted"></i>
                            </div>
                        @endif
                        <div class="mt-2">
                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Change Password</h5>
                    <p class="text-muted font-size-13">Leave these blank if you don't want to change your password.</p>

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                        @error('new_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection