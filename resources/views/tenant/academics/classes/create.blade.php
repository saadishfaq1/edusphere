@extends('layouts.tenant')
@section('title', 'Add Class')
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Class</h4>
                <form method="POST" action="{{ route('classes.store') }}">
                    @csrf
                    @include('tenant.academics.classes._form')
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Class</button>
                        <a href="{{ route('classes.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

