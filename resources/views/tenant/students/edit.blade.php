@extends('layouts.tenant')

@section('title', 'Edit Student')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Student</h4>

                <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @include('tenant.students._form')

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Student</button>
                        <a href="{{ route('students.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

