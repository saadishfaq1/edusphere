<?php

namespace App\Services\Tenant;

use App\Models\TimetableSlot;
use Illuminate\Database\Eloquent\Collection;

class TimetableService
{
    protected array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    public function days(): array
    {
        return $this->days;
    }

    public function forSection(int $sectionId): Collection
    {
        return TimetableSlot::with(['subject', 'employee'])
            ->where('section_id', $sectionId)
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');
    }

    public function createSlot(array $data): TimetableSlot
    {
        $conflict = TimetableSlot::where('section_id', $data['section_id'])
            ->where('day', $data['day'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function ($q2) use ($data) {
                        $q2->where('start_time', '<=', $data['start_time'])
                            ->where('end_time', '>=', $data['end_time']);
                    });
            })
            ->exists();

        if ($conflict) {
            throw new \Exception('This time slot overlaps with an existing class for this section.');
        }

        return TimetableSlot::create($data);
    }

    public function deleteSlot(TimetableSlot $slot): void
    {
        $slot->delete();
    }
}