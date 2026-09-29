@extends('layouts.central')
@section('title', 'Platform Settings')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Platform Branding</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

                <form method="POST" action="{{ route('central.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label d-block">Platform Logo</label>
                        @if ($settings->logo_path)
                            <img src="{{ asset('storage/'.$settings->logo_path) }}" class="rounded border mb-2" height="60">
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">Appears on the platform login page and central dashboard.</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label d-block">Primary Color</label>
                        <input type="color" name="primary_color" value="{{ old('primary_color', $settings->primary_color) }}" class="form-control form-control-color" style="width: 60px; height: 45px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection