<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Payment;

class Violation extends Model
{
    protected $fillable = [
        'user_id', 'license_plate', 'violation_type', 'location', 'date_time',
        'fine_amount', 'status', 'evidence', 'dispute_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}