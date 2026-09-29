@extends('layouts.tenant')
@section('title', 'Import Results')
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Import Complete</h4>

                <div class="alert alert-success">
                    {{ $successCount }} student{{ $successCount === 1 ? '' : 's' }} imported successfully.
                </div>

                @if (count($errors) > 0)
                    <div class="alert alert-warning">
                        <strong>{{ count($errors) }} row(s) could not be imported:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <a href="{{ route('students.index') }}" class="btn btn-primary">View Students</a>
                <a href="{{ route('students.import') }}" class="btn btn-light">Import More</a>
            </div>
        </div>
    </div>
</div>
@endsection