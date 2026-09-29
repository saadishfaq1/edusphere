<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected ?string $status = null)
    {
    }

    public function collection()
    {
        return Invoice::with(['student', 'feeCategory'])
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['Student', 'Admission #', 'Category', 'Title', 'Base Amount', 'Discount', 'Fine', 'Total Due', 'Paid', 'Balance', 'Due Date', 'Status'];
    }

    public function map($invoice): array
    {
        return [
            $invoice->student->full_name ?? '',
            $invoice->student->admission_number ?? '',
            $invoice->feeCategory->name ?? '',
            $invoice->title,
            $invoice->base_amount,
            $invoice->discount_amount,
            $invoice->fine_amount,
            $invoice->total_due,
            $invoice->paid_amount,
            $invoice->balance,
            $invoice->due_date->format('Y-m-d'),
            ucfirst($invoice->status),
        ];
    }
}