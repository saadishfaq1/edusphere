@extends('layouts.tenant')
@section('title', 'Add Section')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Section</h4>
                <form method="POST" action="{{ route('sections.store') }}">
                    @csrf
                    @include('tenant.academics.sections._form')
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Section</button>
                        <a href="{{ route('sections.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

