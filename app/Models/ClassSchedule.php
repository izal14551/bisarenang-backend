<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ClassSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'class_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    public function swimClass()
    {
        return $this->belongsTo(SwimClass::class, 'class_id');
    }

    public function enrollments()
    {
        return $this->hasMany(MemberCourseEnrollment::class, 'schedule_id');
    }

    public function sessionInstances()
    {
        return $this->hasMany(ClassSessionInstance::class, 'schedule_id');
    }

    public function coachAssignments()
    {
        return $this->hasMany(CoachScheduleAssignment::class, 'schedule_id');
    }
}
