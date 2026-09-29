@extends('layouts.tenant')
@section('title', 'Invoice')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="card-title mb-0">{{ $invoice->title }}</h4>
                    <a href="{{ route('fees.print-batch', ['ids' => $invoice->id]) }}" target="_blank" class="btn btn-sm btn-soft-secondary">
                        <i class="ri-printer-line align-bottom"></i> Print Fee Slip
                    </a>
                </div>

                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

                <table class="table table-borderless mb-0">
                    <tr><td>Student</td><td class="text-end fw-semibold">{{ $invoice->student->full_name }}</td></tr>
                    <tr><td>Category</td><td class="text-end">{{ $invoice->feeCategory->name }}</td></tr>
                    <tr><td>Due Date</td><td class="text-end">{{ $invoice->due_date->format('d M Y') }}</td></tr>
                    <tr><td>Base Amount</td><td class="text-end">{{ number_format($invoice->base_amount, 2) }}</td></tr>
                    <tr><td>Discount</td><td class="text-end text-success">- {{ number_format($invoice->discount_amount, 2) }}</td></tr>
                    <tr><td>Fine</td><td class="text-end text-danger">+ {{ number_format($invoice->fine_amount, 2) }}</td></tr>
                    <tr class="border-top"><td class="fw-bold">Total Due</td><td class="text-end fw-bold">{{ number_format($invoice->total_due, 2) }}</td></tr>
                    <tr><td>Paid So Far</td><td class="text-end">{{ number_format($invoice->paid_amount, 2) }}</td></tr>
                    <tr><td class="fw-bold">Balance</td><td class="text-end fw-bold">{{ number_format($invoice->balance, 2) }}</td></tr>
                </table>
            </div>
        </div>

        @if ($invoice->status !== 'paid')
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Record Payment</h5>
                    <form method="POST" action="{{ route('fees.invoices.payments.store', $invoice) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control" max="{{ $invoice->balance }}" value="{{ $invoice->balance }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Method</label>
                            <select name="method" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reference Note</label>
                            <input type="text" name="reference_note" class="form-control" placeholder="e.g. Receipt #4521, verified via WhatsApp">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Paid On</label>
                            <input type="date" name="paid_on" class="form-control" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Record Payment</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Apply Overdue Fine</h5>
                    <form method="POST" action="{{ route('fees.invoices.fine', $invoice) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Fine Amount</label>
                            <input type="number" step="0.01" name="fine_amount" class="form-control" value="{{ $invoice->fine_amount }}">
                        </div>
                        <button type="submit" class="btn btn-danger">Apply Fine</button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Payment History</h5></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Date</th><th>Amount</th><th>Method</th><th>Note</th></tr></thead>
                    <tbody>
                        @forelse ($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->paid_on->format('d M Y') }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td class="text-capitalize">{{ str_replace('_', ' ', $payment->method) }}</td>
                                <td>{{ $payment->reference_note ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

