<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\Tutor;
use App\Models\Review;
use App\Models\Student;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean'
        ];
    }

    /**
     * Lấy danh sách bookings của user (nếu là học sinh)
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'student_id');
    }
    
    /**
     * Kiểm tra user có phải là gia sư không
     */
    public function isTutor()
    {
        try {
            return $this->tutor()->exists();
        } catch (\Exception $e) {
            Log::error('Error in isTutor method: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thông tin gia sư của user (nếu là gia sư)
     */
    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }
    
    /**
     * Lấy thông tin học sinh của user (nếu là học sinh)
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }
    
    /**
     * Kiểm tra user có phải là học sinh không
     */
    public function isStudent()
    {
        try {
            return $this->student()->exists();
        } catch (\Exception $e) {
            Log::error('Error in isStudent method: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra xem học sinh đã hoàn thành buổi học nào với gia sư
     */
    public function hasCompletedBookingWith($tutorId)
    {
        return Booking::where('student_id', $this->id)
            ->where('tutor_id', $tutorId)
            ->where('status', 'completed')
            ->exists();
    }
    
    /**
     * Kiểm tra xem học sinh đã đánh giá gia sư chưa
     */
    public function hasReviewedTutor($tutorId)
    {
        return Review::where('student_id', $this->id)
            ->where('tutor_id', $tutorId)
            ->exists();
    }
}
