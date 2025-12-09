<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use app\Models\SwimMember;
use app\Models\ClassSchedule;

class MemberCourseEnrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'class_id',
        'schedule_id',
        'status',
        'enrollment_date',
        'cancellation_date',
    ];

    public function member()
    {
        return $this->belongsTo(SwimMember::class, 'member_id');
    }

    public function swimClass()
    {
        return $this->belongsTo(SwimClass::class, 'class_id');
    }

    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }

    public function sessionRecords()
    {
        return $this->hasMany(MemberSessionRecord::class, 'enrollment_id');
    }
}
