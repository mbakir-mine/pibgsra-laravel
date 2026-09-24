<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'school_id',
        'family_id',
        'amount',
        'status',
        'method',
        'gateway_provider',
        'gateway_reference',
        'gateway_transaction_id',
        'checkout_url',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }
}
