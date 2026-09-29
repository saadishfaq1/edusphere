@extends('layouts.tenant')
@section('title', 'Leave Requests')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Submit Leave Request</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('hr.leaves.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-control">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">From</label>
                            <input type="date" name="from_date" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">To</label>
                            <input type="date" name="to_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" name="reason" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Leave Requests</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Employee</th><th>Dates</th><th>Reason</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($leaves as $leave)
                            <tr>
                                <td>{{ $leave->employee->full_name }}</td>
                                <td>{{ $leave->from_date->format('d M') }} - {{ $leave->to_date->format('d M Y') }}</td>
                                <td>{{ $leave->reason }}</td>
                                <td>
                                    @php $colors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger']; @endphp
                                    <span class="badge badge-soft-{{ $colors[$leave->status] }}">{{ ucfirst($leave->status) }}</span>
                                </td>
                                <td>
                                    @if ($leave->status === 'pending')
                                        <form method="POST" action="{{ route('hr.leaves.review', $leave) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button class="btn btn-sm btn-soft-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('hr.leaves.review', $leave) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="btn btn-sm btn-soft-danger">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No leave requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

