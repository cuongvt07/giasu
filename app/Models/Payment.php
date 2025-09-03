<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'vnp_txn_ref',
        'amount',
        'payment_method',
        'bank_code',
        'bank_tran_no', 
        'card_type',
        'status',
        'response_data',
        'paid_at'
    ];

    protected $casts = [
        'response_data' => 'array',
        'paid_at' => 'datetime'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
} 