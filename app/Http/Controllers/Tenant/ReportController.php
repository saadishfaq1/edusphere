<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Tenant\FinanceService;
use App\Services\Tenant\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reports,
        protected FinanceService $finance
    ) {
    }

    public function index(Request $request): View
    {
        Gate::authorize('manage-finance');

        $from = $request->query('from');
        $to = $request->query('to');

        return view('tenant.reports.index', [
            'summary' => $this->finance->summary($from, $to),
            'studentCounts' => $this->reports->studentCounts(),
            'feeSummary' => $this->reports->feeSummary(),
            'attendanceSummary' => $this->reports->attendanceSummary($from, $to),
            'examSummary' => $this->reports->examSummary(),
            'from' => $from,
            'to' => $to,
        ]);
    }
}