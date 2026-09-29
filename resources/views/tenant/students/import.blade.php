@extends('layouts.tenant')
@section('title', 'Bulk Import Students')
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-2">Bulk Import Students</h4>
                <p class="text-muted">Use this to migrate all your students at once from another system, instead of adding them one by one.</p>

                <ol class="mb-4">
                    <li>Make sure all Classes and Sections already exist under Academics — imported students are matched to them by name.</li>
                    <li>Download the template below and fill it in — one row per student.</li>
                    <li>Upload the completed file. You'll get a summary of what succeeded and any rows that need fixing.</li>
                </ol>

                <a href="{{ route('students.import.template') }}" class="btn btn-soft-secondary mb-4">
                    <i class="bi bi-download"></i> Download CSV Template
                </a>

                <form method="POST" action="{{ route('students.import.process') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Completed File</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".csv,.xlsx,.xls" required>
                        @error('file') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Import Students</button>
                    <a href="{{ route('students.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection