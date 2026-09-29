<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use App\Models\Invoice;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\Tenant\FeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FeeController extends Controller
{
    public function __construct(protected FeeService $fees)
    {
    }

    public function categories(): View
    {
        Gate::authorize('manage-fees');

        return view('tenant.fees.categories', ['categories' => $this->fees->categories()]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        Gate::authorize('manage-fees');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:recurring,one_time'],
            'is_transport' => ['nullable', 'boolean'],
        ]);
        $validated['is_transport'] = $request->boolean('is_transport');

        $this->fees->createCategory($validated);

        return back()->with('success', 'Fee category added.');
    }

    public function structures(): View
    {
        Gate::authorize('manage-fees');

        return view('tenant.fees.structures', [
            'structures' => $this->fees->structures(),
            'classes' => SchoolClass::orderBy('name')->get(),
            'categories' => $this->fees->categories(),
        ]);
    }

    public function generateClassFees(Request $request): RedirectResponse
    {
        Gate::authorize('manage-fees');

        $validated = $request->validate([
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'title' => ['required', 'string', 'max:150'],
            'due_date' => ['required', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:fee_categories,id'],
            'amounts' => ['nullable', 'array'],
            'amounts.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $categoryAmounts = [];
        foreach ($validated['categories'] ?? [] as $categoryId) {
            $amount = $validated['amounts'][$categoryId] ?? null;
            if ($amount !== null && $amount > 0) {
                $categoryAmounts[$categoryId] = (float) $amount;
            }
        }

        $grouped = $this->fees->generateClassFeesMultiple(
            (int) $validated['school_class_id'],
            $categoryAmounts,
            $validated['title'],
            $validated['due_date']
        );

        if ($grouped->isEmpty()) {
            return back()->with('error', 'No active students found in that class.');
        }

        $ids = $grouped->flatten()->pluck('id')->implode(',');

        return redirect()->route('fees.print-batch', ['ids' => $ids]);
    }
    
    public function printBatch(Request $request)
    {
        Gate::authorize('manage-fees');

        $ids = explode(',', $request->query('ids', ''));

        $invoices = Invoice::with(['student.schoolClass', 'student.section', 'feeCategory'])
            ->whereIn('id', $ids)
            ->get()
            ->groupBy('student_id');

        if ($invoices->isEmpty()) {
            return back()->with('error', 'No invoices found to print.');
        }

        $school = app('tenant');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tenant.fees.fee-slip', compact('invoices', 'school'));

        return $pdf->stream('fee-slips.pdf');
    }

    public function settings(): View
    {
        Gate::authorize('manage-fees');

        return view('tenant.fees.settings', ['school' => app('tenant')]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        Gate::authorize('manage-fees');

        $validated = $request->validate([
            'logo' => ['nullable', 'image', 'max:2048'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_title' => ['nullable', 'string', 'max:150'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
        ]);

        $school = app('tenant');

        if ($request->hasFile('logo')) {
            if ($school->logo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($school->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('school-logos', 'public');
        }

        unset($validated['logo']);

        $school->update($validated);

        return back()->with('success', 'Settings updated successfully.');
    }

    public function scholarships(): View
    {
        Gate::authorize('manage-fees');

        return view('tenant.fees.scholarships', ['scholarships' => $this->fees->scholarships()]);
    }

    public function storeScholarship(Request $request): RedirectResponse
    {
        Gate::authorize('manage-fees');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
        ]);

        $this->fees->createScholarship($validated);

        return back()->with('success', 'Scholarship added.');
    }

    public function invoices(Request $request): View
    {
        Gate::authorize('manage-fees');

        $status = $request->query('status');

        return view('tenant.fees.invoices', [
            'invoices' => $this->fees->invoices($status),
            'status' => $status,
            'students' => Student::where('status', 'active')->orderBy('first_name')->get(),
            'categories' => $this->fees->categories(),
        ]);
    }

    public function storeInvoice(Request $request): RedirectResponse
    {
        Gate::authorize('manage-fees');

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'fee_category_id' => ['required', 'exists:fee_categories,id'],
            'title' => ['required', 'string', 'max:150'],
            'due_date' => ['required', 'date'],
        ]);

        $student = Student::findOrFail($validated['student_id']);

        $this->fees->generateInvoice($student, (int) $validated['fee_category_id'], $validated['title'], $validated['due_date']);

        return back()->with('success', 'Invoice generated.');
    }

    public function showInvoice(Request $request): View
    {
        Gate::authorize('manage-fees');

        $invoice = Invoice::with(['student', 'feeCategory', 'payments'])->findOrFail($request->route('invoice'));

        return view('tenant.fees.invoice-show', compact('invoice'));
    }

    public function recordPayment(Request $request): RedirectResponse
    {
        Gate::authorize('manage-fees');

        $invoice = Invoice::findOrFail($request->route('invoice'));

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['required', 'in:cash,bank_transfer'],
            'reference_note' => ['nullable', 'string', 'max:255'],
            'paid_on' => ['required', 'date'],
        ]);

        $this->fees->recordPayment(
            $invoice,
            (float) $validated['amount'],
            $validated['method'],
            $validated['reference_note'] ?? null,
            $validated['paid_on'],
            auth()->id()
        );

        return redirect()->route('fees.invoices.show', $invoice)->with('success', 'Payment recorded.');
    }

    public function applyFine(Request $request): RedirectResponse
    {
        Gate::authorize('manage-fees');

        $invoice = Invoice::findOrFail($request->route('invoice'));

        $validated = $request->validate(['fine_amount' => ['required', 'numeric', 'min:0']]);

        $this->fees->applyFine($invoice, (float) $validated['fine_amount']);

        return redirect()->route('fees.invoices.show', $invoice)->with('success', 'Fine applied.');
    }

    public function exportInvoices(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        Gate::authorize('manage-fees');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\InvoicesExport($request->query('status')),
            'invoices-'.now()->format('Y-m-d').'.csv'
        );
    }
}