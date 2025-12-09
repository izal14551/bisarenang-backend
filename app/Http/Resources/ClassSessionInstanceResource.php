<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassSessionInstanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'date'      => $this->session_date,
            'start_time' => $this->start_time,
            'end_time'  => $this->end_time,
            'status'    => $this->session_status,
            'attended_count' => $this->actual_attendance_count,

            'class' => $this->whenLoaded('schedule', function () {
                return [
                    'id'   => $this->schedule->swimClass->id ?? null,
                    'name' => $this->schedule->swimClass->name ?? null,
                ];
            }),

            'schedule' => $this->whenLoaded('schedule', function () {
                return [
                    'id'          => $this->schedule->id,
                    'day_of_week' => $this->schedule->day_of_week,
                    'start_time'  => $this->schedule->start_time,
                    'end_time'    => $this->schedule->end_time,
                ];
            }),

            'primary_coach' => $this->whenLoaded('primaryCoach', function () {
                if (!$this->primaryCoach) return null;

                return [
                    'id'         => $this->primaryCoach->id,
                    'full_name'  => $this->primaryCoach->full_name,
                ];
            }),
        ];
    }
}
