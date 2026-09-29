<?php

namespace App\Services\Tenant;

use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Collection;

class ClassService
{
    public function all(): Collection
    {
        return SchoolClass::with('sections')->orderBy('sort_order')->orderBy('name')->get();
    }

    public function create(array $data): SchoolClass
    {
        return SchoolClass::create($data);
    }

    public function update(SchoolClass $schoolClass, array $data): SchoolClass
    {
        $schoolClass->update($data);

        return $schoolClass->fresh();
    }

    public function delete(SchoolClass $schoolClass): void
    {
        $schoolClass->delete();
    }
}