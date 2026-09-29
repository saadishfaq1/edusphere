@extends('layouts.tenant')
@section('title', 'Edit Section')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Section</h4>
                <form method="POST" action="{{ route('sections.update', $section) }}">
                    @csrf
                    @method('PUT')
                    @include('tenant.academics.sections._form')
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Section</button>
                        <a href="{{ route('sections.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

