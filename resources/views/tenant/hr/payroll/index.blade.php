@extends('layouts.tenant')
@section('title', 'Payroll')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Generate Payroll</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('hr.payroll.generate') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-control">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }} — {{ number_format($employee->basic_salary, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Month</label>
                        <input type="month" name="month" class="form-control" value="{{ $month }}">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Allowances</label>
                            <input type="number" step="0.01" name="allowances" class="form-control" value="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Deductions</label>
                            <input type="number" step="0.01" name="deductions" class="form-control" value="0">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Payroll — {{ $month }}</h4>
                <form method="GET" action="{{ route('hr.payroll') }}">
                    <input type="month" name="month" value="{{ $month }}" class="form-control" onchange="this.form.submit()">
                </form>
            </div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Employee</th><th>Net Salary</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($payrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->employee->full_name }}</td>
                                <td>{{ number_format($payroll->net_salary, 2) }}</td>
                                <td>
                                    <span class="badge badge-soft-{{ $payroll->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payroll->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($payroll->status === 'pending')
                                        <form method="POST" action="{{ route('hr.payroll.mark-paid', $payroll) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-soft-success">Mark Paid</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No payroll entries for this month.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

