<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Tenant\FinanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function __construct(protected FinanceService $finance)
    {
    }

    public function income(Request $request): View
    {
        Gate::authorize('manage-finance');

        $from = $request->query('from');
        $to = $request->query('to');

        return view('tenant.finance.income', [
            'incomes' => $this->finance->incomes($from, $to),
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function storeIncome(Request $request): RedirectResponse
    {
        Gate::authorize('manage-finance');

        $validated = $request->validate([
            'source' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['recorded_by'] = auth()->id();

        $this->finance->createIncome($validated);

        return back()->with('success', 'Income recorded.');
    }

    public function expenses(Request $request): View
    {
        Gate::authorize('manage-finance');

        $from = $request->query('from');
        $to = $request->query('to');

        return view('tenant.finance.expenses', [
            'expenses' => $this->finance->expenses($from, $to),
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        Gate::authorize('manage-finance');

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['recorded_by'] = auth()->id();

        $this->finance->createExpense($validated);

        return back()->with('success', 'Expense recorded.');
    }
}