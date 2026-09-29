@extends('layouts.tenant')
@section('title', 'Invoices')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Generate Invoice</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('fees.invoices.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-control">
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->full_name }} ({{ $student->admission_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fee Category</label>
                        <select name="fee_category_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. August 2026 Tuition">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Invoices</h4>
                <select onchange="window.location.href='{{ route('fees.invoices') }}?status='+this.value" class="form-control" style="width:auto;">
                    <option value="">All</option>
                    <option value="unpaid" @selected($status === 'unpaid')>Unpaid</option>
                    <option value="partial" @selected($status === 'partial')>Partial</option>
                    <option value="paid" @selected($status === 'paid')>Paid</option>
                    <option value="overdue" @selected($status === 'overdue')>Overdue</option>
                </select>
            </div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Student</th><th>Title</th><th>Balance</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($invoices as $invoice)
                            @php
                                $colors = ['unpaid' => 'secondary', 'partial' => 'warning', 'paid' => 'success', 'overdue' => 'danger'];
                            @endphp
                            <tr>
                                <td>{{ $invoice->student->full_name }}</td>
                                <td>{{ $invoice->title }}</td>
                                <td>{{ number_format($invoice->balance, 2) }}</td>
                                <td><span class="badge badge-soft-{{ $colors[$invoice->status] }}">{{ ucfirst($invoice->status) }}</span></td>
                                <a href="{{ route('fees.invoices.export') }}?status={{ $status }}" class="btn btn-sm btn-soft-secondary"><i class="bi bi-download"></i> Export</a>
                                <td><a href="{{ route('fees.invoices.show', $invoice) }}" class="btn btn-sm btn-soft-primary">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No invoices yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

