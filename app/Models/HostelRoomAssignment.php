<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelRoomAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'room_number',
        'capacity',
        'current_occupants',
        'floor',
        'status',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}