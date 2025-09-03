<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TutorEarning extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tutor_id',
        'booking_id',
        'amount',
        'platform_fee',
        'total_amount',
        'status',
        'paid_at',
        'transaction_reference',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    /**
     * Lấy thông tin gia sư
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Lấy thông tin buổi học
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Tính toán tự động phí và số tiền gia sư nhận được
     * 
     * @param float $totalAmount Tổng số tiền buổi học
     * @param float $platformFeePercent Phần trăm phí nền tảng (mặc định 10%)
     * @return array Mảng chứa platform_fee và amount
     */
    public static function calculateFees($totalAmount, $platformFeePercent = 10)
    {
        $platformFee = round($totalAmount * ($platformFeePercent / 100), 2);
        $tutorAmount = $totalAmount - $platformFee;
        
        return [
            'platform_fee' => $platformFee,
            'amount' => $tutorAmount
        ];
    }
} 