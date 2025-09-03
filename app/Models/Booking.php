<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id', 
        'subject_id',
        'start_time',
        'end_time',
        'day_of_week',
        'status',
        'notes',
        'price_per_hour',
        'total_amount',
        'completed_at',
        'completion_notes',
        'cancelled_reason',
        'cancelled_by',
        'refund_percentage',
        'reschedule_requested',
        'rescheduled_at',
        'rescheduled_reason',
        'admin_notes',
        'payment_status',
        'student_confirmed',
        'tutor_confirmed'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'completed_at' => 'datetime',
        'rescheduled_at' => 'datetime',
        'price_per_hour' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'refund_percentage' => 'integer',
        'reschedule_requested' => 'boolean',
        'student_confirmed' => 'boolean',
        'tutor_confirmed' => 'boolean',
        'day_of_week' => 'integer'
    ];

    // Các trạng thái thanh toán
    const PAYMENT_STATUS_PENDING = 'pending'; // Chờ thanh toán
    const PAYMENT_STATUS_PAID = 'paid'; // Đã thanh toán
    const PAYMENT_STATUS_REFUNDED = 'refunded'; // Đã hoàn tiền
    const PAYMENT_STATUS_PARTIAL_REFUNDED = 'partial_refunded'; // Hoàn tiền một phần
    
    // Các trạng thái buổi học - Tối ưu hóa
    const STATUS_PENDING = 'pending'; // Chờ thanh toán
    const STATUS_CONFIRMED = 'confirmed'; // Đã thanh toán, chờ diễn ra
    const STATUS_COMPLETED = 'completed'; // Đã hoàn thành
    const STATUS_CANCELLED = 'cancelled'; // Đã hủy

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    /**
     * Lấy các thanh toán của buổi học
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Lấy các yêu cầu đổi lịch của buổi học
     */
    public function rescheduleRequests()
    {
        return $this->hasMany(BookingRescheduleRequest::class);
    }
    
    /**
     * Lấy yêu cầu đổi lịch mới nhất
     */
    public function latestRescheduleRequest()
    {
        return $this->rescheduleRequests()->latest()->first();
    }
    
    /**
     * Kiểm tra xem có yêu cầu đổi lịch đang chờ không
     */
    public function hasPendingRescheduleRequest()
    {
        // Kiểm tra cả trường cơ bản và database
        if ($this->reschedule_requested) {
            return true;
        }
        
        // Kiểm tra trong database xem có yêu cầu đổi lịch nào đang pending không
        return $this->rescheduleRequests()->where('status', 'pending')->exists();
    }
    
    /**
     * Lấy thông tin thu nhập gia sư từ buổi học này
     */
    public function tutorEarning()
    {
        return $this->hasOne(TutorEarning::class);
    }

    /**
     * Kiểm tra xem buổi học đã được thanh toán chưa
     */
    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_STATUS_PAID;
    }

    /**
     * Kiểm tra xem buổi học có thể được đánh dấu hoàn thành không
     */
    public function canBeCompleted()
    {
        // Đơn giản hóa: Chỉ cần thanh toán và buổi học đã kết thúc
        return $this->status === self::STATUS_CONFIRMED && 
               $this->isPaid() && 
               $this->hasEnded();
    }

    /**
     * Kiểm tra xem buổi học có phải đang chờ xác nhận hoàn thành không - Không cần thiết nữa
     */
    public function isPendingCompletion()
    {
        return $this->status === self::STATUS_CONFIRMED && $this->hasEnded();
    }

    /**
     * Tính toán số tiền hoàn trả khi hủy buổi học
     */
    public function calculateRefundAmount()
    {
        if ($this->refund_percentage <= 0) {
            return 0;
        }
        
        return ($this->total_amount * $this->refund_percentage) / 100;
    }

    /**
     * Kiểm tra xem buổi học có sắp bắt đầu không (trong vòng 24 giờ)
     */
    public function isUpcoming()
    {
        return $this->status === self::STATUS_CONFIRMED && 
               now()->diffInHours($this->start_time) <= 24;
    }

    /**
     * Kiểm tra xem buổi học đã diễn ra xong chưa
     */
    public function hasEnded()
    {
        return now()->gt($this->end_time);
    }
} 