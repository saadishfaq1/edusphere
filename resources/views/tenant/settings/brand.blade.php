@extends('layouts.tenant')
@section('title', 'Brand Settings')
@section('content')
<div class="row">
    <div class="col-lg-3">
        @include('tenant.settings._nav')
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-2">Brand Settings</h4>
                <p class="text-muted">Customize your school's logo and accent color across the system and printed documents.</p>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

                <form method="POST" action="{{ route('settings.brand.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label d-block">School Logo</label>
                        @if ($school->logo_path)
                            <img src="{{ asset('storage/'.$school->logo_path) }}" class="rounded border mb-2" height="70">
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">Appears on fee slips, login page, and reports.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Primary Color</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="primary_color" value="{{ old('primary_color', $school->primary_color) }}" class="form-control form-control-color" style="width: 60px; height: 45px;">
                            <span class="text-muted">Used for buttons, sidebar highlights, and links throughout your dashboard.</span>
                        </div>
                        @error('primary_color') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save Brand Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

