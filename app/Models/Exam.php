<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'subject_id',
        'class_level_id',
        'term_id',
        'total_marks',
        'pass_marks',
        'exam_date',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id', 'subject_id')
            ->whereColumn('grades.class_level_id', 'exams.class_level_id')
            ->whereColumn('grades.term_id', 'exams.term_id');
    }

    /**
     * Check if this exam has any grades recorded.
     * Grades link to exams via subject_id + class_level_id + term_id (no exam_id FK).
     */
    public function hasGrades(): bool
    {
        return \DB::table('grades')
            ->where('subject_id', $this->subject_id)
            ->where('class_level_id', $this->class_level_id)
            ->where('term_id', $this->term_id)
            ->exists();
    }
}