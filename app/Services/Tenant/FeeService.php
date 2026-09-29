<?php

namespace App\Services\Tenant;

use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Scholarship;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

class FeeService
{
    public function categories(): Collection
    {
        return FeeCategory::orderBy('name')->get();
    }

    public function createCategory(array $data): FeeCategory
    {
        return FeeCategory::create($data);
    }

    public function structures(): Collection
    {
        return FeeStructure::with(['schoolClass', 'feeCategory'])->get();
    }

    public function generateClassFeesMultiple(int $schoolClassId, array $categoryAmounts, string $title, string $dueDate): Collection
    {
        $schoolId = app('tenant')->id;
        $now = now();

        $students = Student::where('school_class_id', $schoolClassId)
            ->where('status', 'active')
            ->with(['scholarship', 'transportStop.route'])
            ->get();

        if ($students->isEmpty()) {
            return collect();
        }

        // 1. Generate the categories the admin actually picked (tuition, exam fee, etc.)
        foreach ($categoryAmounts as $feeCategoryId => $amount) {
            $structure = FeeStructure::updateOrCreate(
                ['school_class_id' => $schoolClassId, 'fee_category_id' => $feeCategoryId],
                ['amount' => $amount]
            );

            $rows = $students->map(function (Student $student) use ($structure, $feeCategoryId, $title, $dueDate, $schoolId, $now) {
                $discount = $student->scholarship
                    ? $student->scholarship->calculateDiscount($structure->amount)
                    : 0;

                return [
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'fee_category_id' => $feeCategoryId,
                    'title' => $title,
                    'base_amount' => $structure->amount,
                    'discount_amount' => $discount,
                    'fine_amount' => 0,
                    'paid_amount' => 0,
                    'due_date' => $dueDate,
                    'status' => 'unpaid',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->toArray();

            foreach (array_chunk($rows, 500) as $chunk) {
                \Illuminate\Support\Facades\DB::table('invoices')->insert($chunk);
            }
        }

        // 2. Automatically add transport fee for any student with a route assigned — no manual step
        $transportCategory = $this->transportCategory();

        $transportRows = $students
            ->filter(fn (Student $student) => $student->transportStop && $student->transportStop->route)
            ->map(function (Student $student) use ($transportCategory, $title, $dueDate, $schoolId, $now) {
                $routeFee = $student->transportStop->route->monthly_fee;

                $discount = $student->scholarship
                    ? $student->scholarship->calculateDiscount($routeFee)
                    : 0;

                return [
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'fee_category_id' => $transportCategory->id,
                    'title' => $title,
                    'base_amount' => $routeFee,
                    'discount_amount' => $discount,
                    'fine_amount' => 0,
                    'paid_amount' => 0,
                    'due_date' => $dueDate,
                    'status' => 'unpaid',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->values()->toArray();

        foreach (array_chunk($transportRows, 500) as $chunk) {
            \Illuminate\Support\Facades\DB::table('invoices')->insert($chunk);
        }

        return Invoice::with(['student.schoolClass', 'student.section', 'feeCategory'])
            ->where('title', $title)
            ->where('due_date', $dueDate)
            ->where('created_at', $now)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy('student_id');
    }

    public function scholarships(): Collection
    {
        return Scholarship::orderBy('name')->get();
    }

    public function createScholarship(array $data): Scholarship
    {
        return Scholarship::create($data);
    }

    public function generateInvoice(Student $student, int $feeCategoryId, string $title, string $dueDate): Invoice
    {
        $structure = FeeStructure::where('school_class_id', $student->school_class_id)
            ->where('fee_category_id', $feeCategoryId)
            ->firstOrFail();

        $discount = $student->scholarship
            ? $student->scholarship->calculateDiscount($structure->amount)
            : 0;

        return Invoice::create([
            'student_id' => $student->id,
            'fee_category_id' => $feeCategoryId,
            'title' => $title,
            'base_amount' => $structure->amount,
            'discount_amount' => $discount,
            'due_date' => $dueDate,
            'status' => 'unpaid',
        ]);
    }

    public function recordPayment(Invoice $invoice, float $amount, string $method, ?string $referenceNote, string $paidOn, int $recordedBy): Payment
    {
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'method' => $method,
            'reference_note' => $referenceNote,
            'paid_on' => $paidOn,
            'recorded_by' => $recordedBy,
        ]);

        $invoice->paid_amount += $amount;
        $invoice->status = $invoice->paid_amount >= $invoice->total_due ? 'paid' : 'partial';
        $invoice->save();

        \App\Services\Tenant\ActivityLogger::log(
            'payment_recorded',
            "Recorded payment of {$amount} for {$invoice->student->full_name}'s invoice \"{$invoice->title}\"",
            $invoice,
            ['amount' => $amount, 'method' => $method]
        );

        return $payment;
    }

    public function applyFine(Invoice $invoice, float $fineAmount): void
    {
        $invoice->fine_amount = $fineAmount;
        $invoice->status = 'overdue';
        $invoice->save();
    }

    public function invoices(?string $status = null, ?int $studentId = null): Collection
    {
        return Invoice::with(['student', 'feeCategory'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($studentId, fn ($q) => $q->where('student_id', $studentId))
            ->latest()
            ->get();
    }

    protected function transportCategory(): FeeCategory
    {
        return FeeCategory::firstOrCreate(
            ['name' => 'Transport Fee'],
            ['type' => 'recurring', 'is_transport' => true]
        );
    }
}