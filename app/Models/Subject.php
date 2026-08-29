<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_compulsory',
        'education_level_id',
    ];

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }
}