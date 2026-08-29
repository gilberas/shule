<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_order',
        'class_level_id',
    ];

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}