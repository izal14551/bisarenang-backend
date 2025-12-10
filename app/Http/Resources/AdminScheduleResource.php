<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'class_id'    => $this->class_id,
            'day_of_week' => (int) $this->day_of_week,
            'start_time'  => $this->start_time,
            'end_time'    => $this->end_time,
            'coaches'     => $this->coachAssignments->map(function ($assign) {
                return [
                    'assignment_id' => $assign->id,
                    'coach_name'    => $assign->coach->full_name ?? 'Unknown',
                    'is_primary'    => (bool) $assign->is_primary,
                ];
            }),
        ];
    }
}
