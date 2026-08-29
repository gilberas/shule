<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'model',
        'capacity',
        'status',
    ];

    public function assignments()
    {
        return $this->hasMany(StudentBusAssignment::class);
    }
}