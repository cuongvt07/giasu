<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRescheduleOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reschedule_request_id',
        'start_time',
        'end_time',
        'is_selected'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_selected' => 'boolean'
    ];

    /**
     * Lấy yêu cầu đổi lịch liên quan
     */
    public function rescheduleRequest()
    {
        return $this->belongsTo(BookingRescheduleRequest::class, 'booking_reschedule_request_id');
    }
}
