<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MemberSessionRecord extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_ATTENDED = 'attended';
    const STATUS_ABSENT   = 'absent';
    const STATUS_EXPECTED = 'expected';

    protected $fillable = [
        'session_id',
        'enrollment_id',
        'check_in_time',
        'status',
    ];
    public function session()
    {
        return $this->belongsTo(ClassSessionInstance::class, 'session_id');
    }
    public function enrollment()
    {
        return $this->belongsTo(MemberCourseEnrollment::class, 'enrollment_id');
    }
}
