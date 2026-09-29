@extends('layouts.tenant')

@section('title', 'Add Student')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Student</h4>
                @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                    @csrf

                    @include('tenant.students._form')

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Student</button>
                        <a href="{{ route('students.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

