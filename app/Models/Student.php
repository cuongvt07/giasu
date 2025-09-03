<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'parent_name',
        'parent_phone',
        'school',
        'grade',
        'address',
        'bio',
        'status',
    ];

    /**
     * Lấy thông tin user của học sinh
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy danh sách lịch rảnh của học sinh
     */
    public function availabilities()
    {
        return $this->hasMany(StudentAvailability::class);
    }

    /**
     * Lấy danh sách các buổi học của học sinh
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    /**
     * Lấy danh sách môn học mà học sinh quan tâm
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects');
    }
    
    /**
     * Lấy danh sách yêu cầu tìm gia sư của học sinh
     */
    public function requests()
    {
        return $this->hasMany(TutorRequest::class);
    }
    
    /**
     * Lấy danh sách đánh giá mà học sinh đã viết cho gia sư
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Kiểm tra học sinh có trạng thái active không
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
    
    /**
     * Lấy tên đầy đủ của học sinh từ user
     */
    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : 'Không có tên';
    }
    
    /**
     * Lấy email của học sinh từ user
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : 'Không có email';
    }
} 