<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getDashboardRoute(): string
    {
        return match ($this->role) {
            'super_admin' => '/admin/dashboard',
            'admin' => '/admin/dashboard',
            'teacher' => '/teacher/dashboard',
            'accountant' => '/accountant/dashboard',
            'parent' => '/parent/dashboard',
            'student' => '/student/dashboard',
            default => '/dashboard',
        };
    }
}
