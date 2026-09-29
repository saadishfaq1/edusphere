@extends('layouts.tenant')
@section('title', 'Edit Employee')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Employee</h4>
                <form method="POST" action="{{ route('hr.employees.update', $employee) }}">
                    @csrf
                    @method('PUT')
                    @include('tenant.hr.employees._form')
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Employee</button>
                        <a href="{{ route('hr.employees') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

