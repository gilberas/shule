<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBusAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'bus_id',
        'route_id',
        'pickup_point',
        'drop_off_point',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}