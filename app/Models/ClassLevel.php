<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_order',
        'education_level_id',
        'capacity',
    ];

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function stream()
    {
        return $this->hasMany(Stream::class);
    }

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}