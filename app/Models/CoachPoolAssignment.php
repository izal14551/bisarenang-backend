<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachPoolAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'coach_id',
        'pool_id',
        'is_primary',
        'effective_from',
        'effective_until',
    ];

    public function coach()
    {
        return $this->belongsTo(SwimCoach::class, 'coach_id');
    }

    public function pool()
    {
        return $this->belongsTo(PoolLocation::class, 'pool_id');
    }
}
