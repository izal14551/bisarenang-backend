<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachAttendanceLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'session_id',
        'coach_id',
        'check_in_time',
        'status',
    ];
    public function session()
    {
        return $this->belongsTo(ClassSessionInstance::class, 'session_id');
    }
    public function coach()
    {
        return $this->belongsTo(SwimCoach::class, 'coach_id');
    }
}
