<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ClassSessionInstance;
use App\Models\MemberCourseEnrollment;

class MemberSessionRecord extends Model
{
    use HasFactory, SoftDeletes;
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
