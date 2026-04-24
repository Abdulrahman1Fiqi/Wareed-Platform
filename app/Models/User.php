<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'blood_type',
        'phone',
        'city',
        'district',
        'status',
        'last_donation_date',
        'donation_count',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_donation_date' => 'date',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function donorResponses()
    {
        return $this->hasMany(DonorResponse::class, 'donor_id');
    }

    public function notifications()
    {
        return $this->hasMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable_id');
    }
}