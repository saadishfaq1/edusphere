@extends('layouts.tenant')
@section('title', 'Edit Class')
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Class</h4>
                <form method="POST" action="{{ route('classes.update', $class) }}">
                    @csrf
                    @method('PUT')
                    @include('tenant.academics.classes._form')
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Class</button>
                        <a href="{{ route('classes.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

