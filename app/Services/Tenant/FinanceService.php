<?php

namespace App\Services\Tenant;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Payment;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Collection;

class FinanceService
{
    public function incomes(?string $from = null, ?string $to = null): Collection
    {
        return Income::when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->latest('date')
            ->get();
    }

    public function createIncome(array $data): Income
    {
        return Income::create($data);
    }

    public function expenses(?string $from = null, ?string $to = null): Collection
    {
        return Expense::when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->latest('date')
            ->get();
    }

    public function createExpense(array $data): Expense
    {
        return Expense::create($data);
    }

    public function summary(?string $from = null, ?string $to = null): array
    {
        $feeCollection = Payment::when($from, fn ($q) => $q->whereDate('paid_on', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('paid_on', '<=', $to))
            ->sum('amount');

        $otherIncome = Income::when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->sum('amount');

        $operatingExpenses = Expense::when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->sum('amount');

        $payrollPaid = Payroll::where('status', 'paid')
            ->when($from, fn ($q) => $q->whereDate('paid_on', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('paid_on', '<=', $to))
            ->sum('net_salary');

        $totalIncome = $feeCollection + $otherIncome;
        $totalExpenses = $operatingExpenses + $payrollPaid;

        return [
            'fee_collection' => $feeCollection,
            'other_income' => $otherIncome,
            'total_income' => $totalIncome,
            'operating_expenses' => $operatingExpenses,
            'payroll_paid' => $payrollPaid,
            'total_expenses' => $totalExpenses,
            'net_profit' => $totalIncome - $totalExpenses,
        ];
    }
}