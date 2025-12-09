<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SwimClassResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'schedule_type' => $this->schedule_type,
            'max_capacity'  => $this->max_capacity,

            'pool' => [
                'id'   => $this->pool->id ?? null,
                'name' => $this->pool->pool_name ?? null,
            ],

            'schedules' => $this->whenLoaded('schedules', function () {
                return $this->schedules->map(function ($schedule) {
                    return [
                        'id'          => $schedule->id,
                        'day_of_week' => $schedule->day_of_week,
                        'start_time'  => $schedule->start_time,
                        'end_time'    => $schedule->end_time,
                    ];
                });
            }),
        ];
    }
}
