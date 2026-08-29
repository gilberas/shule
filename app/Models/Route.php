<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_point',
        'end_point',
        'via_points',
        'distance_km',
        'travel_time_minutes',
        'status',
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function assignments()
    {
        return $this->hasMany(StudentBusAssignment::class);
    }
}
