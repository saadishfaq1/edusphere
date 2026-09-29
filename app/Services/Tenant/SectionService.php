<?php

namespace App\Services\Tenant;

use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;

class SectionService
{
    public function all(): Collection
    {
        return Section::with('schoolClass')->get();
    }

    public function create(array $data): Section
    {
        return Section::create($data);
    }

    public function update(Section $section, array $data): Section
    {
        $section->update($data);

        return $section->fresh();
    }

    public function delete(Section $section): void
    {
        $section->delete();
    }
}