@extends('layouts.tenant')
@section('title', 'Income')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Record Income</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('finance.income.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Source</label>
                        <input type="text" name="source" class="form-control @error('source') is-invalid @enderror" placeholder="e.g. Donation, Canteen Rent">
                        @error('source') <span class="invalid-feedback">{{ $message }}</span> @enderror
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
                    <button type="submit" class="btn btn-primary">Save Income</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Income Records</h4>
                <form method="GET" class="d-flex gap-2">
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                    <input type="date" name="to" value="{{ $to }}" class="form-control">
                    <button class="btn btn-primary">Filter</button>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Date</th><th>Source</th><th>Description</th><th class="text-end">Amount</th></tr></thead>
                    <tbody>
                        @forelse ($incomes as $income)
                            <tr>
                                <td>{{ $income->date->format('d M Y') }}</td>
                                <td>{{ $income->source }}</td>
                                <td>{{ $income->description ?? '—' }}</td>
                                <td class="text-end">{{ number_format($income->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No income recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($incomes->isNotEmpty())
                        <tfoot>
                            <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-end">{{ number_format($incomes->sum('amount'), 2) }}</td></tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

