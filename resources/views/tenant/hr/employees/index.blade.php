@extends('layouts.tenant')
@section('title', 'Employees')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Employees</h4>
                <a href="{{ route('hr.employees.create') }}" class="btn btn-primary"><i class="bx bx-plus"></i> Add Employee</a>
            </div>
            <div class="card-body">
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Code</th><th>Name</th><th>Designation</th><th>Salary</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>{{ $employee->employee_code }}</td>
                                <td>{{ $employee->full_name }}</td>
                                <td>{{ $employee->designation }}</td>
                                <td>{{ number_format($employee->basic_salary, 2) }}</td>
                                <td>
                                    <span class="badge badge-soft-{{ $employee->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </td>
                                <td><a href="{{ route('hr.employees.edit', $employee) }}" class="btn btn-sm btn-soft-primary">Edit</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No employees yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

