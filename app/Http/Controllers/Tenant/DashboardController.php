<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Tenant\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboard)
    {
    }

    public function index(): View
    {
        return view('tenant.dashboard', [
            'stats' => $this->dashboard->stats(),
            'feeTrend' => $this->dashboard->feeCollectionTrend(),
            'studentsByClass' => $this->dashboard->studentsByClass(),
            'attendanceBreakdown' => $this->dashboard->attendanceBreakdown(),
            'topClasses' => $this->dashboard->topClasses(),
        ]);
    }
}