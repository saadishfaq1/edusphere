<?php

namespace App\Services\Tenant;

use App\Models\Student;
use App\Models\TransportRoute;
use App\Models\TransportStop;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

class TransportService
{
    public function vehicles(): Collection
    {
        return Vehicle::orderBy('vehicle_number')->get();
    }

    public function createVehicle(array $data): Vehicle
    {
        return Vehicle::create($data);
    }

    public function routes(): Collection
    {
        return TransportRoute::with(['vehicle', 'stops'])->orderBy('name')->get();
    }

    public function createRoute(array $data): TransportRoute
    {
        return TransportRoute::create($data);
    }

    public function addStop(TransportRoute $route, array $data): TransportStop
    {
        $data['transport_route_id'] = $route->id;
        $data['sort_order'] = $route->stops()->count();

        return TransportStop::create($data);
    }

    public function assignStudent(Student $student, ?int $stopId): Student
    {
        $student->update(['transport_stop_id' => $stopId]);

        return $student->fresh();
    }
}