<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #222; margin: 0; padding: 0; }
        .slip { padding: 30px; page-break-after: always; }
        .slip:last-child { page-break-after: auto; }
        .header { display: table; width: 100%; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header .logo-cell { display: table-cell; width: 70px; vertical-align: middle; }
        .header .logo-cell img { width: 60px; height: 60px; object-fit: contain; }
        .header .name-cell { display: table-cell; vertical-align: middle; padding-left: 10px; }
        .header .name-cell h2 { margin: 0; font-size: 20px; }
        .header .name-cell p { margin: 2px 0 0; color: #666; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin: 15px 0; text-decoration: underline; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 0; }
        .info-table td.label { font-weight: bold; width: 130px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.items th, table.items td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        table.items th { background: #f0f0f0; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background: #f7f7f7; }
        .bank-box { border: 1px solid #999; padding: 10px; margin-top: 15px; font-size: 12px; }
        .signature { margin-top: 40px; display: table; width: 100%; }
        .signature .cell { display: table-cell; width: 50%; text-align: center; }
        .signature .line { border-top: 1px solid #333; margin: 30px 20px 5px; }
    </style>
</head>
<body>
    @foreach ($invoices as $studentId => $studentInvoices)
        @php
            $student = $studentInvoices->first()->student;
            $grandTotal = $studentInvoices->sum(fn($inv) => $inv->total_due);
            $dueDate = $studentInvoices->first()->due_date;
            $title = $studentInvoices->first()->title;
        @endphp
        <div class="slip">
            <div class="header">
                <div class="logo-cell">
                    @if ($school->logo_path)
                        <img src="{{ public_path('storage/'.$school->logo_path) }}">
                    @endif
                </div>
                <div class="name-cell">
                    <h2>{{ $school->name }}</h2>
                    <p>Fee Voucher</p>
                </div>
            </div>

            <div class="title">{{ $title }}</div>

            <table class="info-table">
                <tr>
                    <td class="label">Student Name</td>
                    <td>{{ $student->full_name }}</td>
                    <td class="label">Admission No.</td>
                    <td>{{ $student->admission_number }}</td>
                </tr>
                <tr>
                    <td class="label">Class</td>
                    <td>{{ $student->schoolClass->name ?? '—' }} {{ $student->section ? '- '.$student->section->name : '' }}</td>
                    <td class="label">Due Date</td>
                    <td>{{ $dueDate->format('d M Y') }}</td>
                </tr>
            </table>

            <table class="items">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Discount</th>
                        <th class="text-right">Fine</th>
                        <th class="text-right">Payable</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studentInvoices as $i => $invoice)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $invoice->feeCategory->name }}</td>
                            <td class="text-right">{{ number_format($invoice->base_amount, 2) }}</td>
                            <td class="text-right">{{ number_format($invoice->discount_amount, 2) }}</td>
                            <td class="text-right">{{ number_format($invoice->fine_amount, 2) }}</td>
                            <td class="text-right">{{ number_format($invoice->total_due, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="5" class="text-right">Grand Total</td>
                        <td class="text-right">{{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            @if ($school->bank_name || $school->bank_account_number)
                <div class="bank-box">
                    <strong>Bank Payment Details:</strong><br>
                    @if ($school->bank_name) Bank: {{ $school->bank_name }}<br> @endif
                    @if ($school->bank_account_title) Account Title: {{ $school->bank_account_title }}<br> @endif
                    @if ($school->bank_account_number) Account Number: {{ $school->bank_account_number }}<br> @endif
                    Please send payment proof to the school office to have this invoice marked as paid.
                </div>
            @endif

            <div class="signature">
                <div class="cell">
                    <div class="line"></div>
                    Parent / Guardian Signature
                </div>
                <div class="cell">
                    <div class="line"></div>
                    Received By (Office)
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>

