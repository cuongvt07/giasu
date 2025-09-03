<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'education_level',
        'university',
        'major',
        'teaching_experience',
        'bio',
        'avatar',
        'certification_files',
        'hourly_rate',
        'is_verified',
        'status',
        'total_teaching_hours',
        'rating'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'rating' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'tutor_subjects')
            ->withPivot('price_per_hour', 'experience_details')
            ->withTimestamps();
    }

    public function classLevels()
    {
        return $this->belongsToMany(ClassLevel::class, 'tutor_class_levels')
            ->withPivot('price_per_hour', 'experience_details')
            ->withTimestamps();
    }

    public function recommendations()
    {
        return $this->hasMany(AiRecommendation::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Lấy lịch rảnh của gia sư
     */
    public function availabilities()
    {
        return $this->hasMany(TutorAvailability::class);
    }

    /**
     * Lấy thông tin thu nhập của gia sư
     */
    public function earnings()
    {
        return $this->hasMany(TutorEarning::class);
    }
}
