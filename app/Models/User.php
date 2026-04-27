<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
class User extends Authenticatable implements MustVerifyEmail
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


    public function badge(): string
    {
        return match(true) {
            $this->donation_count >= 10 => 'Hero Donor',
            $this->donation_count >= 3  => 'Trusted Donor',
            $this->donation_count >= 1  => 'Active Donor',
            default                     => 'New Donor',
        };
    }

    public function badgeClass(): string
    {
        return match($this->badge()) {
            'Hero Donor'    => 'badge-hero',
            'Trusted Donor' => 'badge-trusted',
            'Active Donor'  => 'badge-active',
            default         => 'badge-new',
        };
    }
    
}