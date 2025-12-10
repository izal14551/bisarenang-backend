<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CoachScheduleAssignment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'coach_id',
        'schedule_id',
        'is_primary',
        'effective_from',
        'effective_until',
    ];
    public function coach()
    {
        return $this->belongsTo(SwimCoach::class, 'coach_id');
    }
    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }
}
