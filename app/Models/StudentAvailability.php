<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAvailability extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'day_of_week',
        'date',
        'start_time',
        'end_time',
        'is_recurring',
        'status',
    ];

    /**
     * Định nghĩa các thuộc tính cần chuyển đổi kiểu dữ liệu
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'date' => 'date',
        'is_recurring' => 'boolean',
        'day_of_week' => 'integer',
    ];

    /**
     * Các giá trị mặc định cho các thuộc tính
     *
     * @var array
     */
    protected $attributes = [
        'is_recurring' => false,
        'status' => 'active',
    ];

    /**
     * Lấy học sinh sở hữu lịch rảnh này
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Kiểm tra xem một lịch rảnh có trùng với lịch học đã đặt không
     */
    public function hasBookingConflicts()
    {
        $student = $this->student;
        
        $query = Booking::where('student_id', $student->id)
            ->where('status', '!=', 'cancelled')
            ->where('day_of_week', $this->day_of_week);
            
        // Nếu lịch rảnh có ngày cụ thể, chỉ kiểm tra lịch học cho ngày đó
        if ($this->date) {
            $query->whereDate('start_time', $this->date);
        }
        
        return $query->where(function($query) {
                $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $this->end_time])
                    ->orWhere(function($q) {
                        $q->where('start_time', '<=', $this->start_time)
                          ->where('end_time', '>=', $this->end_time);
                    });
            })->exists();
    }

    /**
     * Lấy tên ngày trong tuần theo tiếng Việt
     */
    public function getDayNameAttribute()
    {
        $days = [
            0 => 'Chủ Nhật',
            1 => 'Thứ Hai',
            2 => 'Thứ Ba',
            3 => 'Thứ Tư',
            4 => 'Thứ Năm',
            5 => 'Thứ Sáu',
            6 => 'Thứ Bảy',
        ];

        return $days[$this->day_of_week] ?? 'Không xác định';
    }

    /**
     * Lấy khoảng thời gian đẹp (8:00 - 10:00)
     */
    public function getTimeRangeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }
    
    /**
     * Kiểm tra xem lịch rảnh có khả dụng không (active)
     */
    public function isAvailable()
    {
        return $this->status === 'active';
    }
} 