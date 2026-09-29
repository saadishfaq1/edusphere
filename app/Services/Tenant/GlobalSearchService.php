<?php

namespace App\Services\Tenant;

use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Support\Collection;

class GlobalSearchService
{
    public function search(string $query): Collection
    {
        if (strlen($query) < 2) {
            return collect();
        }

        $results = collect();

        Student::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('admission_number', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->each(function ($student) use ($results) {
                $results->push([
                    'type' => 'Student',
                    'icon' => 'bi-person',
                    'title' => $student->full_name,
                    'subtitle' => 'Admission #'.$student->admission_number,
                    'url' => route('students.edit', $student),
                ]);
            });

        Employee::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('employee_code', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->each(function ($employee) use ($results) {
                $results->push([
                    'type' => 'Employee',
                    'icon' => 'bi-briefcase',
                    'title' => $employee->full_name,
                    'subtitle' => $employee->designation,
                    'url' => route('hr.employees.edit', $employee),
                ]);
            });

        Invoice::where('title', 'like', "%{$query}%")
            ->orWhereHas('student', fn ($q) => $q->where('admission_number', 'like', "%{$query}%"))
            ->with('student')
            ->limit(5)
            ->get()
            ->each(function ($invoice) use ($results) {
                $results->push([
                    'type' => 'Invoice',
                    'icon' => 'bi-receipt',
                    'title' => $invoice->title,
                    'subtitle' => $invoice->student->full_name ?? '',
                    'url' => route('fees.invoices.show', $invoice),
                ]);
            });

        return $results;
    }
}