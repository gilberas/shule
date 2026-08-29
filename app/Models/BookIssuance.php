<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookIssuance extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'student_id',
        'issued_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}