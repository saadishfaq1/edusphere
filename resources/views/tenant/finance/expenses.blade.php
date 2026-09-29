@extends('layouts.tenant')
@section('title', 'Expenses')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Record Expense</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('finance.expenses.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" placeholder="e.g. Utilities, Maintenance, Supplies">
                        @error('category') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror">
                        @error('amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Expense</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Expense Records</h4>
                <form method="GET" class="d-flex gap-2">
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                    <input type="date" name="to" value="{{ $to }}" class="form-control">
                    <button class="btn btn-primary">Filter</button>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Date</th><th>Category</th><th>Description</th><th class="text-end">Amount</th></tr></thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td>{{ $expense->date->format('d M Y') }}</td>
                                <td>{{ $expense->category }}</td>
                                <td>{{ $expense->description ?? '—' }}</td>
                                <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No expenses recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($expenses->isNotEmpty())
                        <tfoot>
                            <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-end">{{ number_format($expenses->sum('amount'), 2) }}</td></tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

