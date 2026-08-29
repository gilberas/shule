<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_structure_id',
        'student_id',
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'receipt_number',
        'notes',
    ];

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}