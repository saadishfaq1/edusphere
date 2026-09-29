@extends('layouts.central')
@section('title', 'Dashboard')
@section('content')

<div class="row g-3 mb-3">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Schools</div>
                    <div class="stat-value">{{ $summary['total_schools'] }}</div>
                </div>
                <div class="stat-icon bg-primary-soft text-primary"><i class="bi bi-buildings"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Active</div>
                    <div class="stat-value">{{ $summary['active_schools'] }}</div>
                </div>
                <div class="stat-icon bg-success-soft text-success"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">On Trial</div>
                    <div class="stat-value">{{ $summary['trial_schools'] }}</div>
                </div>
                <div class="stat-icon bg-warning-soft text-warning"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Students (all schools)</div>
                    <div class="stat-value">{{ number_format($summary['total_students']) }}</div>
                </div>
                <div class="stat-icon bg-info-soft text-info"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header"><h2 class="card-title mb-0">School Growth (Last 6 Months)</h2></div>
            <div class="card-body">
                <div data-chart="area"
                     data-series='[{"name":"New Schools","data":{{ json_encode($growthTrend['data']) }}}]'
                     data-cats='{{ json_encode($growthTrend['labels']) }}'
                     data-colors='["#14C38E"]'
                     data-height="300"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header"><h2 class="card-title mb-0">Status Breakdown</h2></div>
            <div class="card-body">
                <div data-chart="donut"
                     data-series='[{{ $statusBreakdown['active'] }},{{ $statusBreakdown['trial'] }},{{ $statusBreakdown['suspended'] }}]'
                     data-labels='["Active","Trial","Suspended"]'
                     data-colors='["#14C38E","#F59E0B","#EF4444"]'
                     data-height="240"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header"><h2 class="card-title mb-0">Plan Distribution</h2></div>
            <div class="card-body">
                @if (array_sum($planDistribution['data']) > 0)
                    <div data-chart="bar"
                         data-series='[{"name":"Schools","data":{{ json_encode($planDistribution['data']) }}}]'
                         data-cats='{{ json_encode($planDistribution['labels']) }}'
                         data-colors='["#0EA5E9"]'
                         data-height="240"></div>
                @else
                    <div class="text-center text-muted py-5">No plans assigned to any school yet.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center h-100">
                <i class="bi bi-plus-circle fs-1 text-primary mb-3"></i>
                <h5>Onboard a New School</h5>
                <p class="text-muted">Add a new school to the platform, assign a plan, and create their first admin account.</p>
                <a href="{{ route('central.schools.create') }}" class="btn btn-primary">Onboard New School</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.js"></script>
<script src="{{ asset('assets-elite/js/charts.js') }}"></script>
@endpush
