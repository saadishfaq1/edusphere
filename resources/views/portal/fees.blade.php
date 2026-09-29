@extends('layouts.portal')
@section('title', 'Fees')
@section('content')
<a href="{{ route('portal.dashboard') }}" class="btn btn-sm btn-light mb-3"><i class="bi bi-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h5 class="mb-0">{{ $student->full_name }} — Fees</h5></div>
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead><tr><th>Title</th><th>Due Date</th><th>Total</th><th>Paid</th><th>Balance</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($invoices as $invoice)
                    @php $colors = ['unpaid' => 'secondary', 'partial' => 'warning', 'paid' => 'success', 'overdue' => 'danger']; @endphp
                    <tr>
                        <td>{{ $invoice->title }}</td>
                        <td>{{ $invoice->due_date->format('d M Y') }}</td>
                        <td>{{ number_format($invoice->total_due, 2) }}</td>
                        <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                        <td>{{ number_format($invoice->balance, 2) }}</td>
                        <td><span class="badge bg-{{ $colors[$invoice->status] }}">{{ ucfirst($invoice->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No invoices yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection