<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_request_id',
        'donor_id',
        'status',
        'responded_at',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}
