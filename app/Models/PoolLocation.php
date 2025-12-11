<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoolLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pool_name',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function coaches()
    {
        return $this->belongsToMany(SwimCoach::class, 'coach_pool_assignments', 'pool_id', 'coach_id')
            ->withPivot('effective_from', 'effective_until', 'is_primary')
            ->withTimestamps();
    }


    public function classes()
    {
        return $this->hasMany(SwimClass::class, 'pool_id');
    }
}
