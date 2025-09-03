<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class, 'tutor_subjects')
            ->withPivot('price_per_hour', 'experience_details')
            ->withTimestamps();
    }

    public function learningAnalytics()
    {
        return $this->hasMany(LearningAnalytic::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
