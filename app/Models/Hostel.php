<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'gender',  // 'male', 'female', or 'co-ed'
        'total_rooms',
        'total_capacity',
        'status',
    ];

    public function roomAssignments()
    {
        return $this->hasMany(HostelRoomAssignment::class);
    }
}