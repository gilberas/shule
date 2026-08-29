<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_number',
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'address',
        'class_level_id',
        'stream_id',
        'academic_year_id',
        'parent_id',
        'parent_name',
        'parent_contact',
        'enrollment_date',
        'status',
    ];

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function parent()
    {
        return $this->belongsTo(SchoolParent::class);
    }
}