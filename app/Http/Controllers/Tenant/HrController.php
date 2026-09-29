<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Services\Tenant\HrService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class HrController extends Controller
{
    public function __construct(protected HrService $hr)
    {
    }

    public function employees(): View
    {
        Gate::authorize('manage-hr');

        return view('tenant.hr.employees.index', ['employees' => $this->hr->employees()]);
    }

    public function createEmployee(): View
    {
        Gate::authorize('manage-hr');

        return view('tenant.hr.employees.create', [
            'users' => \App\Models\User::whereDoesntHave('employee')->orderBy('name')->get(),
        ]);
    }

    public function storeEmployee(Request $request): RedirectResponse
    {
        Gate::authorize('manage-hr');

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'designation' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_joining' => ['required', 'date'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive,terminated'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $this->hr->createEmployee($validated);

        return redirect()->route('hr.employees')->with('success', 'Employee added successfully.');
    }

    public function editEmployee(Request $request): View
    {
        Gate::authorize('manage-hr');

        $employee = Employee::findOrFail($request->route('employee'));

        return view('tenant.hr.employees.edit', [
            'employee' => $employee,
            'users' => \App\Models\User::where(function ($q) use ($employee) {
                $q->whereDoesntHave('employee')->orWhere('id', $employee->user_id);
            })->orderBy('name')->get(),
        ]);
    }

    public function updateEmployee(Request $request): RedirectResponse
    {
        Gate::authorize('manage-hr');

        $employee = Employee::findOrFail($request->route('employee'));

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'designation' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_joining' => ['required', 'date'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive,terminated'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $this->hr->updateEmployee($employee, $validated);

        return redirect()->route('hr.employees')->with('success', 'Employee updated successfully.');
    }

    public function leaves(): View
    {
        Gate::authorize('manage-hr');

        return view('tenant.hr.leaves.index', [
            'leaves' => $this->hr->leaveRequests(),
            'employees' => $this->hr->employees(),
        ]);
    }

    public function storeLeave(Request $request): RedirectResponse
    {
        Gate::authorize('manage-hr');

        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $this->hr->createLeaveRequest($validated);

        return back()->with('success', 'Leave request submitted.');
    }

    public function reviewLeave(Request $request): RedirectResponse
    {
        Gate::authorize('manage-hr');

        $leave = LeaveRequest::findOrFail($request->route('leave'));

        $validated = $request->validate(['status' => ['required', 'in:approved,rejected']]);

        $this->hr->reviewLeaveRequest($leave, $validated['status'], auth()->id());

        return back()->with('success', 'Leave request updated.');
    }

    public function payroll(Request $request): View
    {
        Gate::authorize('manage-hr');

        $month = $request->query('month', now()->format('Y-m'));

        return view('tenant.hr.payroll.index', [
            'payrolls' => $this->hr->payrolls($month),
            'employees' => $this->hr->employees(),
            'month' => $month,
        ]);
    }

    public function generatePayroll(Request $request): RedirectResponse
    {
        Gate::authorize('manage-hr');

        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'month' => ['required', 'date_format:Y-m'],
            'allowances' => ['nullable', 'numeric', 'min:0'],
            'deductions' => ['nullable', 'numeric', 'min:0'],
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        $this->hr->generatePayroll(
            $employee,
            $validated['month'],
            (float) ($validated['allowances'] ?? 0),
            (float) ($validated['deductions'] ?? 0)
        );

        return redirect()->route('hr.payroll', ['month' => $validated['month']])->with('success', 'Payroll generated.');
    }

    public function markPaid(Request $request): RedirectResponse
    {
        Gate::authorize('manage-hr');

        $payroll = Payroll::findOrFail($request->route('payroll'));

        $this->hr->markPayrollPaid($payroll);

        return back()->with('success', 'Marked as paid.');
    }
}