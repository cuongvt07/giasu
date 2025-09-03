<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRescheduleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'reason',
        'requested_by',
        'status',
        'notes',
        'response_note',
    ];

    /**
     * Lấy thông tin buổi học liên quan
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Lấy các tùy chọn thời gian
     */
    public function options()
    {
        return $this->hasMany(BookingRescheduleOption::class);
    }

    /**
     * Lấy gia sư yêu cầu đổi lịch
     */
    public function requester()
    {
        return $this->belongsTo(Tutor::class, 'requested_by');
    }

    /**
     * Kiểm tra trạng thái yêu cầu
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
