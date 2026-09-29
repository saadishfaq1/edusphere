<?php

namespace App\Services\Tenant;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Collection;

class HrService
{
    public function employees(): Collection
    {
        return Employee::orderBy('first_name')->get();
    }

    public function createEmployee(array $data): Employee
    {
        $data['employee_code'] = $this->generateEmployeeCode();

        return Employee::create($data);
    }

    public function updateEmployee(Employee $employee, array $data): Employee
    {
        $employee->update($data);

        return $employee->fresh();
    }

    protected function generateEmployeeCode(): string
    {
        $year = now()->format('Y');

        $last = Employee::withTrashed()
            ->where('employee_code', 'like', "EMP-{$year}-%")
            ->orderByDesc('employee_code')
            ->value('employee_code');

        $sequence = $last ? ((int) substr($last, -4)) + 1 : 1;

        return "EMP-{$year}-".str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function leaveRequests(): Collection
    {
        return LeaveRequest::with('employee')->latest()->get();
    }

    public function createLeaveRequest(array $data): LeaveRequest
    {
        return LeaveRequest::create($data);
    }

    public function reviewLeaveRequest(LeaveRequest $leave, string $status, int $reviewedBy): LeaveRequest
    {
        $leave->update(['status' => $status, 'reviewed_by' => $reviewedBy]);

        return $leave->fresh();
    }

    public function payrolls(?string $month = null): Collection
    {
        return Payroll::with('employee')
            ->when($month, fn ($q) => $q->where('month', $month))
            ->latest()
            ->get();
    }

    public function generatePayroll(Employee $employee, string $month, float $allowances, float $deductions): Payroll
    {
        $net = $employee->basic_salary + $allowances - $deductions;

        return Payroll::updateOrCreate(
            ['employee_id' => $employee->id, 'month' => $month],
            [
                'basic_salary' => $employee->basic_salary,
                'allowances' => $allowances,
                'deductions' => $deductions,
                'net_salary' => $net,
                'status' => 'pending',
            ]
        );
    }

    public function markPayrollPaid(Payroll $payroll): Payroll
    {
        $payroll->update(['status' => 'paid', 'paid_on' => now()->format('Y-m-d')]);

        return $payroll->fresh();
    }
}