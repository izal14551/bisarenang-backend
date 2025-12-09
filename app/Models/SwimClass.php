<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SwimClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pool_id',
        'name',
        'description',
        'schedule_type',
        'max_capacity',
        'is_active',
    ];

    public function pool()
    {
        return $this->belongsTo(PoolLocation::class, 'pool_id');
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'class_id');
    }

    public function enrollments()
    {
        return $this->hasMany(MemberCourseEnrollment::class, 'class_id');
    }
}
