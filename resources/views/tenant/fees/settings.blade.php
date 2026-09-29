@extends('layouts.tenant')
@section('title', 'School & Bank Settings')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">School Logo & Bank Details</h4>
                <p class="text-muted">These appear on every printed fee slip.</p>

                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

                <form method="POST" action="{{ route('fees.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">School Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if ($school->logo_path)
                            <img src="{{ asset('storage/'.$school->logo_path) }}" class="mt-2 rounded border" height="60">
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $school->bank_name) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Title</label>
                        <input type="text" name="bank_account_title" class="form-control" value="{{ old('bank_account_title', $school->bank_account_title) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number', $school->bank_account_number) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

