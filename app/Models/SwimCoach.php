<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SwimCoach extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'phone_number',
        'is_active',
    ];

    public function pools()
    {
        return $this->belongsToMany(PoolLocation::class, 'coach_pool_assignments', 'coach_id', 'pool_id')
            ->withPivot('effective_from', 'effective_until', 'is_primary')
            ->withTimestamps();
    }


    public function scheduleAssignments()
    {
        return $this->hasMany(CoachScheduleAssignment::class, 'coach_id');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(CoachAttendanceLog::class, 'coach_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
