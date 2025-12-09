<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SwimMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'phone_number',
        'date_of_birth',
        'is_active',
    ];

    public function enrollments()
    {
        return $this->hasMany(MemberCourseEnrollment::class, 'member_id');
    }
}
