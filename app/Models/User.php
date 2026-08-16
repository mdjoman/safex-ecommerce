<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is sales person
     *
     * @return bool
     */
    public function isSales()
    {
        return $this->role === 'sales';
    }

    /**
     * Check if user is customer/user
     *
     * @return bool
     */
    public function isUser()
    {
        return $this->role === 'user' || $this->role === null;
    }

    /**
     * Get user role label
     *
     * @return string
     */
    public function getRoleLabelAttribute()
    {
        $roles = [
            'admin' => 'Administrator',
            'sales' => 'Sales Person',
            'user' => 'Customer',
        ];
        return $roles[$this->role] ?? 'Customer';
    }

    /**
     * Get user role badge class
     *
     * @return string
     */
    public function getRoleBadgeAttribute()
    {
        $badges = [
            'admin' => 'success',
            'sales' => 'info',
            'user' => 'secondary',
        ];
        return $badges[$this->role] ?? 'secondary';
    }

    /**
     * Relationships
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_sales');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
