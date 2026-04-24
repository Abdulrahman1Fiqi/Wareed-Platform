<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'blood_type',
        'units_needed',
        'urgency',
        'status',
        'contact_person',
        'contact_phone',
        'notes',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function donorResponses()
    {
        return $this->hasMany(DonorResponse::class);
    }

    public function acceptedDonors()
    {
        return $this->hasMany(DonorResponse::class)->where('status', 'accepted');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function compatibleBloodTypes(): array
    {
        $compatibility = [
            'A+'  => ['A+', 'A-', 'O+', 'O-'],
            'A-'  => ['A-', 'O-'],
            'B+'  => ['B+', 'B-', 'O+', 'O-'],
            'B-'  => ['B-', 'O-'],
            'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'AB-' => ['A-', 'B-', 'AB-', 'O-'],
            'O+'  => ['O+', 'O-'],
            'O-'  => ['O-'],
        ];

        return $compatibility[$this->blood_type] ?? [];
    }
}