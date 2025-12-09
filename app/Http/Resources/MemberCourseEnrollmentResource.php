<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberCourseEnrollmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'status'           => $this->status,
            'enrollment_date'  => $this->enrollment_date,
            'cancellation_date' => $this->cancellation_date,

            'class' => $this->whenLoaded('swimClass', function () {
                return [
                    'id'   => $this->swimClass->id,
                    'name' => $this->swimClass->name,
                    'schedule_type' => $this->swimClass->schedule_type,
                ];
            }),

            'schedule' => $this->whenLoaded('schedule', function () {
                if (!$this->schedule) {
                    return null;
                }

                return [
                    'id'          => $this->schedule->id,
                    'day_of_week' => $this->schedule->day_of_week,
                    'start_time'  => $this->schedule->start_time,
                    'end_time'    => $this->schedule->end_time,
                ];
            }),
        ];
    }
}
