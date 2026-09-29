<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TransportRoute;
use App\Services\Tenant\TransportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TransportController extends Controller
{
    public function __construct(protected TransportService $transport)
    {
    }

    public function vehicles(): View
    {
        Gate::authorize('manage-transport');

        return view('tenant.transport.vehicles', ['vehicles' => $this->transport->vehicles()]);
    }

    public function storeVehicle(Request $request): RedirectResponse
    {
        Gate::authorize('manage-transport');

        $validated = $request->validate([
            'vehicle_number' => ['required', 'string', 'max:50'],
            'driver_name' => ['nullable', 'string', 'max:150'],
            'driver_phone' => ['nullable', 'string', 'max:20'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $this->transport->createVehicle($validated);

        return back()->with('success', 'Vehicle added successfully.');
    }

    public function routes(): View
    {
        Gate::authorize('manage-transport');

        return view('tenant.transport.routes', [
            'routes' => $this->transport->routes(),
            'vehicles' => $this->transport->vehicles(),
        ]);
    }

    public function storeRoute(Request $request): RedirectResponse
    {
        Gate::authorize('manage-transport');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'monthly_fee' => ['required', 'numeric', 'min:0'],
        ]);

        $this->transport->createRoute($validated);

        return back()->with('success', 'Route created successfully.');
    }

    public function showRoute(Request $request): View
    {
        Gate::authorize('manage-transport');

        $route = TransportRoute::with('stops')->findOrFail($request->route('route'));

        return view('tenant.transport.route-show', compact('route'));
    }

    public function storeStop(Request $request): RedirectResponse
    {
        Gate::authorize('manage-transport');

        $route = TransportRoute::findOrFail($request->route('route'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'pickup_time' => ['required'],
        ]);

        $this->transport->addStop($route, $validated);

        return back()->with('success', 'Stop added successfully.');
    }

    public function assignments(): View
    {
        Gate::authorize('manage-transport');

        return view('tenant.transport.assignments', [
            'students' => Student::with('transportStop.route')->where('status', 'active')->orderBy('first_name')->get(),
            'routes' => $this->transport->routes(),
        ]);
    }

    public function assignStudent(Request $request): RedirectResponse
    {
        Gate::authorize('manage-transport');

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'transport_stop_id' => ['nullable', 'exists:transport_stops,id'],
        ]);

        $student = Student::findOrFail($validated['student_id']);

        $this->transport->assignStudent($student, $validated['transport_stop_id'] ?? null);

        return back()->with('success', 'Transport assignment updated.');
    }
}