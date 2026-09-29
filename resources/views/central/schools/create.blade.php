@extends('layouts.central')
@section('title', 'Onboard School')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Onboard New School</h4>
                <form method="POST" action="{{ route('central.schools.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">School Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Organization (optional)</label>
                        <select name="organization_id" class="form-control">
                            <option value="">None — standalone school</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Group this school under an organization if it's one branch of a multi-campus school.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch Name (optional)</label>
                        <input type="text" name="branch_name" class="form-control" placeholder="e.g. Peshawar Campus">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subdomain</label>
                        <div class="input-group">
                            <input type="text" name="subdomain" class="form-control @error('subdomain') is-invalid @enderror" value="{{ old('subdomain') }}" placeholder="e.g. sunrise">
                            <span class="input-group-text">.edusphere.test</span>
                        </div>
                        @error('subdomain') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan</label>
                        <select name="plan_id" class="form-control">
                            <option value="">No plan (assign later)</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr class="my-4">
                    <h5 class="mb-3">First Admin Account</h5>
                    <p class="text-muted font-size-13">This creates the school's own login — they'll use this to sign in and manage their school.</p>

                    <div class="mb-3">
                        <label class="form-label">Admin Name</label>
                        <input type="text" name="admin_name" class="form-control @error('admin_name') is-invalid @enderror" value="{{ old('admin_name') }}">
                        @error('admin_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Email</label>
                        <input type="email" name="admin_email" class="form-control @error('admin_email') is-invalid @enderror" value="{{ old('admin_email') }}">
                        @error('admin_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Password</label>
                        <input type="password" name="admin_password" class="form-control @error('admin_password') is-invalid @enderror">
                        @error('admin_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Onboard School</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


