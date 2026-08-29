<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'frequency',
        'education_level_id',
        'class_level_id',
        'subject_id',
        'term_id',
        'is_active',
    ];

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}