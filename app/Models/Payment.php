<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Violation;

class Payment extends Model
{
    protected $fillable = ['violation_id', 'amount', 'payment_status', 'transaction_id', 'payment_date'];

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }
}