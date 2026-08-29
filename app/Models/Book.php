<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category',
        'publisher',
        'published_year',
        'total_copies',
        'available_copies',
        'description',
    ];

    public function issuances()
    {
        return $this->hasMany(BookIssuance::class);
    }
}