@extends('layouts.tenant')
@section('title', 'Subjects')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Subject</h4>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('subjects.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Subject Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g. MTH101">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Subject</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Subjects</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Name</th><th>Code</th></tr></thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr><td>{{ $subject->name }}</td><td>{{ $subject->code ?? '—' }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-4">No subjects yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

