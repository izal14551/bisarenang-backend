<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSessionInstance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'schedule_id',
        'primary_coach_id',
        'session_date',
        'start_time',
        'end_time',
        'session_status',
        'actual_attendance_count',
    ];

    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }

    public function primaryCoach()
    {
        return $this->belongsTo(SwimCoach::class, 'primary_coach_id');
    }

    public function memberSessionRecords()
    {
        return $this->hasMany(MemberSessionRecord::class, 'session_id');
    }

    public function coachAttendanceLogs()
    {
        return $this->hasMany(CoachAttendanceLog::class, 'session_id');
    }
}
