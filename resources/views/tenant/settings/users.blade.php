@extends('layouts.tenant')
@section('title', 'Users & Roles')
@section('content')
<div class="row">
    <div class="col-lg-3">
        @include('tenant.settings._nav')
    </div>

    <div class="col-lg-9">
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Add User</h4>
                        <form method="POST" action="{{ route('settings.users.store') }}">
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
                                <label class="form-label">Role</label>
                                <select name="role" class="form-control">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Create User</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">All Users</h4></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered align-middle table-nowrap mb-0">
                                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('settings.users.role', $user) }}">
                                                    @csrf
                                                    <select name="role" class="form-control form-control-sm" onchange="this.form.submit()">
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role }}" @selected($user->roles->pluck('name')->contains($role))>{{ $role }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <span class="badge badge-soft-{{ $user->email_verified_at ? 'success' : 'secondary' }}">
                                                    {{ $user->email_verified_at ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($user->id !== auth()->id())
                                                    <form method="POST" action="{{ route('settings.users.toggle-status', $user) }}">
                                                        @csrf
                                                        <button class="btn btn-sm btn-soft-{{ $user->email_verified_at ? 'danger' : 'success' }}">
                                                            {{ $user->email_verified_at ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">No users yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

